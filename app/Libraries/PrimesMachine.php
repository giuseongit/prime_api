<?php

namespace App\Libraries;

class PrimesMachine
{
    public static function next(int $num): int
    {
        $primes = self::getPrimes($num + 1, PHP_INT_MAX, $stop_after=1);
        return $primes[0];
    }

    public static function prev(int $num): int
    {
        $primes = self::getPrimes(1, $num - 1);
        return $primes[count($primes) - 1];
    }

    public static function between(int $a, int $b): array
    {
        return self::getPrimes($a, $b);
    }

    private static function getPrimes(int $from, int $to, int $stop_after=0): array
    {
        $primes = [];
        for ($i = $from; $i <= $to; $i++) {
            if (self::isPrime($i)) {
                $primes[] = $i;
                if ($stop_after > 0 && count($primes) >= $stop_after) {
                    break;
                }
            }
        }
        return $primes;
    }

    private static function isPrime(int $num): bool
    {
        if ($num <= 1) {
            return false;
        }
        if ($num <= 3) {
            return true;
        }
        if ($num % 2 == 0 || $num % 3 == 0) {
            return false;
        }
        for ($i = 5; $i <= sqrt($num); $i+=2) {
            if ($num % $i == 0) {
                return false;
            }
        }
        return true;
    }
}