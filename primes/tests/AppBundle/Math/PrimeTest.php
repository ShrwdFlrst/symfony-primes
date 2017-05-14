<?php

use AppBundle\Math\Prime;
use PHPUnit\Framework\TestCase;

class PrimeTest extends TestCase
{
    /**
     * Check whether the first 200 primes are generated correctly.
     */
    public function testGetPrimes()
    {
        $expectedPrimesTo200 = [
            2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61,
            67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113, 127, 131, 137,
            139, 149, 151, 157, 163, 167, 173, 179, 181, 191, 193, 197, 199
        ];
        $prime = new Prime();

        $primes = $prime->getPrimes(count($expectedPrimesTo200));
        $this->assertEquals($expectedPrimesTo200, $primes);
    }

    /**
     * Check if given number is/is not a prime.
     *
     * @param int  $number
     * @param bool $expected
     * @dataProvider isPrimeProvider
     */
    public function testIsPrime(int $number, bool $expected)
    {
        $prime = new Prime();

        $this->assertEquals($expected, $prime->isPrime($number));
    }

    /**
     * @return array
     */
    public function isPrimeProvider()
    {
        return [
            [1, false],
            [2, true],
            [3, true],
            [4, false],
            [5, true],
            [6, false],
            [7, true],
            [8, false],
            [9, false],
            [10, false],
            [11, true],
            [857, true],
            [858, false],
            [881, true],
            [991, true],
            [1001, false],
        ];
    }
}