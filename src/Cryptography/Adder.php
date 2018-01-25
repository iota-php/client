<?php

declare(strict_types=1);

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IOTA\Cryptography;

class Adder
{
    public static function add(array $a, array $b): array
    {
        $length = max(\count($a), \count($b));
        $out = [];
        $carry = 0;

        for ($i = 0; $i < $length; ++$i) {
            $a_i = $i < \count($a) ? $a[$i] : 0;
            $b_i = $i < \count($b) ? $b[$i] : 0;
            $f_a = self::fullAdd($a_i, $b_i, $carry);
            $out[$i] = $f_a[0];
            $carry = $f_a[1];
        }

        return $out;
    }

    protected static function any(int $a, int $b): int
    {
        $s = $a + $b;
        if ($s > 0) {
            return 1;
        }
        if ($s < 0) {
            return -1;
        }

        return 0;
    }

    protected static function cons(int $a, int $b): int
    {
        if ($a === $b) {
            return $a;
        }

        return 0;
    }

    /**
     * Sums up two trits and returns a resulting trit.
     *
     * @param int $a
     * @param int $b
     *
     * @return int
     */
    protected static function sum(int $a, int $b): ?int
    {
        $s = $a + $b;

        switch ($s) {
            case 2: return -1;
            case -2: return 1;
            default: return $s;
        }
    }

    protected static function fullAdd(int $a, int $b, int $c): array
    {
        $s_a = self::sum($a, $b);
        $c_a = self::cons($a, $b);
        $c_b = self::cons($s_a, $c);
        $c_out = self::any($c_a, $c_b);
        $s_out = self::sum($s_a, $c);

        return [$s_out, $c_out];
    }
}
