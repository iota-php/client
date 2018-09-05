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

namespace IOTA\Util;

use InvalidArgumentException;

class TryteUtil
{
    /**
     * A mapping from character to trit values.
     *
     * @var array
     */
    public const TRYTE_TO_TRITS_MAP = [
        '9' => [0,  0,  0],
        'A' => [1,  0,  0],
        'B' => [-1, 1,  0],
        'C' => [0,  1,  0],
        'D' => [1,  1,  0],
        'E' => [-1, -1,  1],
        'F' => [0,  -1,  1],
        'G' => [1,  -1,  1],
        'H' => [-1,  0,  1],
        'I' => [0,  0,  1],
        'J' => [1,  0,  1],
        'K' => [-1,  1,  1],
        'L' => [0,  1,  1],
        'M' => [1,  1,  1],
        'N' => [-1, -1, -1],
        'O' => [0, -1, -1],
        'P' => [1, -1, -1],
        'Q' => [-1,  0, -1],
        'R' => [0,  0, -1],
        'S' => [1,  0, -1],
        'T' => [-1,  1, -1],
        'U' => [0,  1, -1],
        'V' => [1,  1, -1],
        'W' => [-1, -1,  0],
        'X' => [0, -1,  0],
        'Y' => [1, -1,  0],
        'Z' => [-1,  0,  0],
    ];

    /**
     * Creates a new Tryte from the given 3 trits.
     *
     * @param int $t1
     * @param int $t2
     * @param int $t3
     *
     * @return string
     */
    public static function fromTrits(int $t1, int $t2, int $t3): string
    {
        // 9 will be casted to int...
        return (string) \array_search([$t1, $t2, $t3], self::TRYTE_TO_TRITS_MAP, true);
    }

    /**
     * Creates a new Tryte from the given 3 trits.
     *
     * @param string $tryte
     *
     * @return int[]
     */
    public static function toTrits(string $tryte): array
    {
        if (!isset(self::TRYTE_TO_TRITS_MAP[$tryte])) {
            throw new InvalidArgumentException('Invalid tryte: ' . $tryte);
        }

        return self::TRYTE_TO_TRITS_MAP[$tryte];
    }
}
