<?php

namespace AppBundle\Command;

use AppBundle\Math\Prime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate the multiplication tables for given number of prime numbers.
 * Class PrimesCommand
 * @package AppBundle\Command
 */
class PrimesCommand extends Command
{
    /**
     * @var Prime
     */
    private $prime;

    /**
     * PrimesCommand constructor.
     * @param string $commandName
     * @param Prime $prime
     */
    public function __construct(string $commandName, Prime $prime)
    {

        parent::__construct($commandName);
        $this->prime = $prime;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate the multiplication tables for given number of prime numbers.')
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
        $timeStart = microtime(true);
        $count = (int) $input->getOption('count');
        $primes = $this->prime->getPrimes($count);
        $separator = "\t";
        $primeMultiTable = '';

        // Append 0 as we're making a table, and we need the first column/row
        // to be the header and contain the primes themselves.
        array_unshift($primes, 0);

        // Create the table, row by row.
        foreach ($primes as $k => $rowItem) {
            foreach($primes as $i => $colItem) {
                if ($k > 0 && $k === $i) {
                    // The number is multiplied by itself, so highlight it.
                    $primeMultiTable .= '<comment>' . $rowItem * $colItem . $separator . '</comment>';
                } elseif ($k === 0 || $i === 0) {
                    // We're in either the first row, or the nth row but first column, highlight it.
                    $primeMultiTable .= '<info>' . ($rowItem + $colItem) . $separator . '</info>';
                } else {
                    $primeMultiTable .= $rowItem * $colItem . $separator;
                }
            }
            $primeMultiTable .= PHP_EOL;
        }

        $output->writeln($primeMultiTable);

        $timeEnd = microtime(true);
        $elapsed = $timeEnd - $timeStart;

        $output->writeln(sprintf('Elapsed time: %s microseconds%s', $elapsed, PHP_EOL));
    }
}