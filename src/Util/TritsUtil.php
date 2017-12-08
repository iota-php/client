<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\Util;

use Techworker\IOTA\Type\Trytes;

/**
 * Class TritsUtil.
 *
 * A utility to handle trit related tasks.
 */
class TritsUtil
{
    /**
     * Gets a Trytes instance of the current trits.
     *
     * @param int[] $trits
     *
     * @return Trytes
     */
    public static function toTrytes(array $trits): Trytes
    {
        if (0 !== \count($trits) % 3) {
            throw new \RuntimeException('Unable to create tryte from trits.');
        }

        $trytes = '';
        $length = \count($trits);
        for ($i = 0; $i < $length; $i += 3) {
            $trytes .= TryteUtil::createFromTrits($trits[$i], $trits[$i + 1], $trits[$i + 2]);
        }

        return new Trytes($trytes);
    }

    /**
     * Converts the given trits to a bigint.
     *
     * @param array $trits
     * @param int   $base
     *
     * @return string
     */
    public static function toInt(array $trits, int $base = 3): string
    {
        $bigInt = \gmp_init(0);
        foreach ($trits as $index => $value) {
            $bigInt = \gmp_add($bigInt, \gmp_mul($value, \gmp_pow($base, $index)));
        }

        return \gmp_strval($bigInt);
    }

    /**
     * @param string $int
     * @param int $hashLength
     * @return array
     */
    public static function fromInt(string $int, int $hashLength): array
    {
        $trits = [];
        if (\is_int($int)) {
            $absoluteValue = $int < 0 ? -$int : $int;
            while ($absoluteValue > 0) {
                $remainder = $absoluteValue % 3;
                $absoluteValue = floor($absoluteValue / 3);

                if ($remainder > 1) {
                    $remainder = -1;
                    ++$absoluteValue;
                }

                $trits[] = $remainder;
            }
            if ($int < 0) {
                $length = \count($trits);
                for ($i = 0; $i < $length; ++$i) {
                    $trits[$i] = -$trits[$i];
                }
            }

            return $trits;
        }

        return self::convertBigintToBase($int, 3, $hashLength);
    }

    /**
     * Converts the given trits to a byte array.
     *
     * @param int[] $trits
     *
     * @return array
     */
    public static function toBytes(array $trits): array
    {
        $bigInt = self::toInt($trits);

        return self::convertBigintToBytes($bigInt);
    }

    /**
     * Converts the given bytes to a trit array.
     *
     * @param array $bytes
     * @param int   $hashLength
     *
     * @return array
     */
    public static function fromBytes(array $bytes, int $hashLength): array
    {
        $bigInt = self::convertBytesToBigInt($bytes);

        return self::fromInt($bigInt, $hashLength);
    }

    /**
     * Converts the given bytes to a big integer value.
     *
     * @param array $bytes
     *
     * @return string
     */
    protected static function convertBytesToBigInt(array $bytes): string
    {
        $sig = $bytes[0] >= 0 ? 1 : -1;
        if ($sig === -1) {
            for ($pos = 47; $pos >= 0; --$pos) {
                $sub = ($bytes[$pos] & 0xFF) - 1;
                $bytes[$pos] = $sub <= 0x7F ? $sub : $sub - 0x100;
                if ($bytes[$pos] !== -1) {
                    break;
                }
            }

            $bytes = array_map(function ($x) {
                return ~$x;
            }, $bytes);
        }

        $result = 0;
        $rev = array_reverse($bytes, false);
        foreach ($rev as $pos => $x) {
            $result = gmp_add($result, gmp_mul(gmp_and($x, 0xFF), gmp_pow(2, $pos * 8)));
        }

        return gmp_strval(gmp_mul($result, $sig));
    }

    /**
     * Converts the given bigint to bytes.
     *
     * @param string $bigInt
     *
     * @return array
     */
    protected static function convertBigintToBytes(string $bigInt): array
    {
        $bytesArrayTemp = [];
        for ($pos = 0; $pos < 48; ++$pos) {
            $bytesArrayTemp[] = (int) (gmp_abs($bigInt) >> $pos * 8) % (1 << 8);
        }

        $bytesArray = array_map(function ($x) {
            return ($x <= 0x7F) ? $x : $x - 0x100;
        }, array_reverse($bytesArrayTemp));

        if (gmp_cmp($bigInt, 0) < 0) {
            // 1-compliment
            $bytesArray = array_map(function ($x) {
                return ~$x;
            }, $bytesArray);

            for ($pos = \count($bytesArray) - 1; $pos >= 0; --$pos) {
                $add = ($bytesArray[$pos] & 0xFF) + 1;
                $bytesArray[$pos] = ($add <= 0x7F) ? $add : $add - 0x100;
                if (0 !== $bytesArray[$pos]) {
                    break;
                }
            }
        }

        return $bytesArray;
    }

    /**
     * Converts the given big integer value to a trit array.
     *
     * @param string $bigInt
     * @param int    $base
     * @param int    $length
     * @param bool   $returnEarly Ignores the given length and returns as soon as the remainder is "0"
     *
     * @return int[]
     */
    protected static function convertBigintToBase(string $bigInt, int $base, int $length): array
    {
        $result = [];

        $is_negative = gmp_cmp($bigInt, 0) < 0;
        $quotient = gmp_abs($bigInt);

        // fuck me! python "//" is not a comment :-D
        $MAX = gmp_div(gmp_sub($base, 1), 2);
        if ($is_negative) {
            $MAX = gmp_div($base, 2);
        }

        for ($i = 0; $i < $length; ++$i) {
            list($quotient, $remainder) = gmp_div_qr($quotient, $base);

            if (gmp_cmp($remainder, $MAX) > 0) {
                // Lend 1 to the next place so we can make this digit negative.
                $quotient = gmp_add($quotient, 1);
                $remainder = gmp_sub($remainder, $base);
            }
            if ($is_negative) {
                $remainder = gmp_mul($remainder, -1);
            }

            $result[] = gmp_intval($remainder);
        }

        return $result;
    }
}
