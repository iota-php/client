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
        return false !== filter_var($neighborUri, FILTER_VALIDATE_URL);
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
}
