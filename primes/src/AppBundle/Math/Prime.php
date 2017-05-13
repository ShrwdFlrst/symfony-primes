<?php

namespace AppBundle\Math;

/**
 * Class Prime
 * @package AppBundle\Math
 */
class Prime
{
    /**
     * @param int $count
     * @param int $max
     * @return \int[]
     */
    public function getPrimes(int $count, int $max = 10000)
    {
        $primes = [];

        foreach (range(0, $max) as $number) {
            if ($this->isPrime($number)) {
                $primes[] = $number;
            }

            if (count($primes) === $count) {
                break;
            }
        }

        return $primes;
    }

    /**
     * @param int $number
     * @see https://en.wikipedia.org/wiki/Primality_test#Pseudocode
     * @return bool
     */
    public function isPrime(int $number)
    {
        if ($number <= 1) {
            return false;
        } elseif ($number <= 3) {
            return true;
        } elseif ($number % 2 === 0 || $number % 3 === 0) {
            return false;
        }

        $i = 5;
        while ($i * $i <= $number) {
            if ($number % $i === 0 || $number % ($i + 2) === 0) {
                return false;
            }
            $i += 6;
        }

        return true;
    }
}