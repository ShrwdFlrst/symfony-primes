<?php

use AppBundle\Math\PrimeGenerator;
use PHPUnit\Framework\TestCase;

class PrimeGeneratorTest extends TestCase
{
    /**
     * @param int  $number
     * @param bool $expected
     * @dataProvider isPrimeProvider
     */
    public function testIsPrime(int $number, bool $expected)
    {
        $primeGenerator = new PrimeGenerator();

        $this->assertEquals($expected, $primeGenerator->isPrime($number));
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