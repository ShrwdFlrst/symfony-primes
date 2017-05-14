<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $output;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }


    /**
     * @When I run :command
     */
    public function iRun($command)
    {
        $this->output = shell_exec("php bin/console " . $command);
    }

    /**
     * @Then I should see :pattern in the output
     */
    public function iShouldSeeInTheOutput($pattern)
    {
//        if (strpos($this->output, $string) === false) {
        if (preg_match($pattern, $this->output) !== 1) {
            throw new \Exception(sprintf('Did not see "%s" in output "%s"', $pattern, $this->output));
        }
    }
}
