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

/**
 * Class Validator.
 *
 * A validator class with static validation functionality.
 */
class ValidatorUtil
{
    /**
     * Gets a value indicating whether the given uri string is valid.
     *
     * @param string $neighborUri
     *
     * @return bool
     */
    public static function isNeighborUri(string $neighborUri): bool
    {
        return false !== \filter_var($neighborUri, FILTER_VALIDATE_URL);
    }

    /**
     * Validates each item in the given list of items by checking if the item
     * type is the given type.
     *
     * @param array  $items
     * @param string $type
     *
     * @return bool
     */
    public static function isArrayOf(array $items, string $type): bool
    {
        foreach ($items as $item) {
            if (false === ($item instanceof $type)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if input is list of correct trytes
     *
     * @param array $hashedArrays
     *
     * @return bool
     */
    public static function isArrayOfHashes(array $hashedArrays)
    {
        foreach ($hashedArrays as $hash) {
            if (strlen($hash) === 90) {
                if (!self::isTrytes($hash, 90)) {
                    return false;
                }
            } else {
                if (!self::isTrytes($hash, 81)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks if input is correct trytes consisting of A-Z9
     *
     * @param string $trytes
     * @param mixed $length
     *
     * @return bool
     */
    public static function isTrytes(string $trytes, $length = '0,')
    {
        return preg_match('/^[9A-Z]{' . $length . '}$/', $trytes) !== 0 ? true : false;
    }
}
