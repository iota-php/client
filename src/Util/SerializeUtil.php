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

use Techworker\IOTA\SerializeInterface;

/**
 * Class TritsUtil.
 *
 * A utility to handle trit related tasks.
 */
class SerializeUtil
{

    /**
     * Serializes an array of serializables.
     *
     * @param SerializeInterface[] $serializables
     * @return array
     */
    public static function serializeArray(array $serializables): array
    {
        $result = [];
        foreach ($serializables as $key => $serializable) {
            $result[$key] = $serializable->serialize();
        }

        return $result;
    }
}
