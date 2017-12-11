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

namespace Techworker\IOTA\Tests\Util;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Util\ValidatorUtil;

class ValidatorUtilTest extends TestCase
{
    public function testIsArrayOf()
    {
        $array = [new ValidatorUtil(), new ValidatorUtil(), new ValidatorUtil()];
        static::assertTrue(ValidatorUtil::isArrayOf($array, ValidatorUtil::class));

        $array[] = new self();
        static::assertFalse(ValidatorUtil::isArrayOf($array, ValidatorUtil::class));

        $array = [];
        static::assertTrue(ValidatorUtil::isArrayOf($array, ValidatorUtil::class));
    }

    public function testIsNeighborUri()
    {
        static::assertTrue(ValidatorUtil::isNeighborUri('udp://8.8.8.8:14265'));
        static::assertTrue(ValidatorUtil::isNeighborUri('udp://8.8.8.8'));
        static::assertFalse(ValidatorUtil::isNeighborUri('abc'));
    }
}