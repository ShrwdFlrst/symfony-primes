<?php

namespace AppBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class CommandContext
 */
class CommandContext extends RawMinkContext
{
    /**
     * @var string
     */
    private $consoleAppClass;

    /**
     * @var \Symfony\Component\Console\Application
     */
    private $application;

    /**
     * @var array
     */
    private $registeredCommands;

    /**
     * @var array
     */
    private $loadedCommands;

    /**
     * @var \Symfony\Component\Console\Tester\CommandTester
     */
    private $tester;

    /**
     * @var \Exception
     */
    private $commandException;

    /**
     * @var array
     */
    private $commandParameters;

    /**
     * @var string
     */
    private $runCommand;

    /**
     * @var array
     */
    private $listeners;

    /**
     * @var int
     */
    private $exitCode;

    /**
     * @param string $consoleAppClass
     * @param array  $registeredCommands
     * @param array  $listeners
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($consoleAppClass, array $registeredCommands = array(), array $listeners = array())
    {
        $this->checkClassIsLoaded($consoleAppClass);

        $this->consoleAppClass    = $consoleAppClass;
        $this->registeredCommands = $registeredCommands;
        $this->loadedCommands     = array();
        $this->listeners          = $listeners;
        $this->commandParameters  = array();
        $this->exitCode           = 0;

        $this->useContext('exception', new ExceptionContext());
    }

    /**
     * @Given /^I run a command "([^"]*)"$/
     */
    public function iRunACommand($command)
    {
        $commandInstance = $this->getCommand($command);
        $this->tester    = new CommandTester($commandInstance);

        try {

            $this->exitCode = $this
                ->tester
                ->execute(
                    $this->getCommandParams($command)
                )
            ;

            $this->commandException = null;

        } catch (\Exception $exception) {

            $this->commandException = $exception;
            $this->exitCode         = $exception->getCode();
        }

        $this->runCommand        = $command;
        $this->commandParameters = array();
    }

    /**
     * @Given /^I run a command "([^"]*)" with parameters:$/
     */
    public function iRunACommandWithParameters($command, PyStringNode $parameterJson)
    {
        $this->commandParameters = json_decode($parameterJson->getRaw(), true);

        if (null === $this->commandParameters) {
            throw new \InvalidArgumentException(
                "PyStringNode could not be converted to json."
            );
        }

        $this->iRunACommand($command);
    }

    /**
     * @Then /^The command exception "([^"]*)" should be thrown$/
     */
    public function theCommandExceptionShouldBeThrown($exceptionClass)
    {
        $this->checkThatCommandHasRun();

        $this
            ->getSubcontext('exception')
            ->setException($this->commandException)
            ->assertException($exceptionClass)
        ;
    }

    /**
     * @Then /^The command exit code should be (\d+)$/
     */
    public function theCommandExitCodeShouldBe($exitCode)
    {
        $this->checkThatCommandHasRun();

        assertEquals($exitCode, $this->exitCode);
    }

    /**
     * @Then /^I should see "([^"]*)" in the command output$/
     */
    public function iShouldSeeInTheCommandOutput($regexp)
    {
        $this->checkThatCommandHasRun();

        assertRegExp($regexp, $this->tester->getDisplay());
    }

    /**
     * @Then /^The command exception "([^"]*)" with message "([^"]*)" should be thrown$/
     */
    public function theCommandExceptionWithMessageShouldBeThrown($exceptionClass, $exceptionMessage)
    {
        $this->checkThatCommandHasRun();

        $this
            ->getSubcontext('exception')
            ->setException($this->commandException)
            ->assertException($exceptionClass)
        ;

        $this
            ->getSubcontext('exception')
            ->assertExceptionMessage($exceptionMessage)
        ;
    }

    /**
     * @return \Symfony\Component\Console\Application
     *
     * @throws \LogicException
     */
    public function getApplication()
    {
        if (null !== $this->application) {
            return $this->application;
        }

        $callingContext = $this->getMainContext();

        if (!$callingContext instanceof KernelAwareInterface) {
            throw new \LogicException(
                "CommandContext or MainContext must implement 'KernelAwareInterface'"
            );
        }

        $kernel = $callingContext->getKernel();

        if (null === $kernel) {
            throw new \LogicException(
                "The kernel hasn't been initialized yet. Please use this method only in a step method."
            );
        }

        $this->application = new $this->consoleAppClass($kernel);

        $this->processConsoleEventListeners();

        return $this->application;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \InvalidArgumentException
     */
    public function getCommand($command)
    {
        $command = (string) $command;

        if ($this->isLoaded($command)) {
            return $this->loadedCommands[$command];
        }

        if (!$this->isRegistered($command)) {
            throw new \InvalidArgumentException(
                sprintf('Command with name "%s" is not registered with the Context', $command)
            );
        }

        $commandInstance = new $this->registeredCommands[$command]();
        $application     = $this->getApplication();

        $application->add($commandInstance);

        return $this->loadedCommands[$command] = $commandInstance;
    }


    /**
     * @param string $commandName
     * @param string $commandClass
     */
    public function registerCommand($commandName, $commandClass)
    {
        $this->checkClassIsLoaded($commandClass);

        $this->registeredCommands[(string) $commandName] = $commandClass;
    }

    /**
     * @param string $commandName
     *
     * @return bool
     */
    public function unregisterCommand($commandName)
    {
        $commandName = (string) $commandName;

        if (!isset($this->registeredCommands[$commandName])) {
            return false;
        }

        unset($this->registeredCommands[$commandName]);

        return true;
    }

    /**
     * @param string $command
     *
     * @return bool
     */
    public function isRegistered($command)
    {
        return (isset($this->registeredCommands[(string) $command]));
    }

    /**
     * @param string $command
     *
     * @return bool
     */
    public function isLoaded($command)
    {
        return (isset($this->loadedCommands[(string) $command]));
    }

    /**
     * @param string $class
     *
     * @throws \InvalidArgumentException
     */
    private function checkClassIsLoaded($class)
    {
        if (!class_exists((string) $class)) {
            throw new \InvalidArgumentException(
                'Class "%s" could not be found or autoloaded.'
            );
        }
    }

    /**
     * @return bool
     *
     * @throws \LogicException
     */
    private function checkThatCommandHasRun()
    {
        if (null === $this->runCommand) {
            throw new \LogicException(
                "You first need to run a command to check to use this step"
            );
        }

        return true;
    }

    /**
     * Processes the subscribers and listeners for this Application
     */
    private function processConsoleEventListeners()
    {
        if (null === $this->application) {
            return null;
        }

        if (!empty($this->listeners)) {

            $dispatcher = new EventDispatcher();

            $listeners = array_merge(
                array(
                    'subscriber' => array(),
                    'listener'   => array()
                ),
                $this->listeners
            );

            foreach ($listeners['listener'] as $event => $listener) {
                $priority = 0;

                if (is_array($listeners)) {
                    list($listener, $priority) = $listener;
                }

                $dispatcher->addListener($event, $listener, $priority);
            }

            foreach ($listeners['subscriber'] as $subscriber) {
                $dispatcher->addSubscriber($subscriber);
            }

            $this
                ->application
                ->setDispatcher($dispatcher)
            ;
        }
    }

    /**
     * @param string $command
     *
     * @return array
     */
    private function getCommandParams($command)
    {
        $default = array(
            'command' => $command
        );

        return array_merge(
            $this->commandParameters,
            $default
        );
    }
}