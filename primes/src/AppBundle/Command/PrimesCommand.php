<?php

namespace AppBundle\Command;

use AppBundle\Math\Prime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrimesCommand extends Command
{
    protected $defaultName;
    /**
     * @var Prime
     */
    private $prime;

    /**
     * PrimesCommand constructor.
     * @param null|string $defaultName
     * @param Prime $prime
     */
    public function __construct($defaultName, Prime $prime)
    {
        $this->defaultName = $defaultName;

        parent::__construct();
        $this->prime = $prime;
    }

    protected function configure()
    {
        $this
            ->setName($this->defaultName)
            ->setDescription('Greet someone')
            ->addOption(
                'count',
                null,
                InputOption::VALUE_REQUIRED,
                'How many primes?',
                10
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = (int) $input->getOption('count');
        $primes = $this->prime->getPrimes($count);
        $blank = ' ';
        $separator = "\t";
        array_unshift($primes, $blank);

        $primeMultiTable = '';

        foreach ($primes as $k => $rowItem) {
            foreach($primes as $i => $colItem) {
                if ($k > 0 && $k === $i) {
                    $primeMultiTable .= '<comment>' . $rowItem * $colItem . $separator . '</comment>';
                } elseif ($k === 0 || $i === 0) {
                    $primeMultiTable .= '<info>'.trim($rowItem . $colItem) . $separator . '</info>';
                } else {
                    $primeMultiTable .= $rowItem * $colItem . $separator;
                }
            }
            $primeMultiTable .= PHP_EOL;
        }

        $output->write($primeMultiTable);
    }
}