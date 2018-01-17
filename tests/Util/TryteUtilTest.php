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

namespace Techworker\IOTA\Tests\Util;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Util\TryteUtil;

/**
 * Class TryteUtilTest.
 *
 * @coversNothing
 */
class TryteUtilTest extends TestCase
{
    public function testToTrits()
    {
        foreach (TryteUtil::TRYTE_TO_TRITS_MAP as $tryte => $expectedTrits) {
            static::assertEquals($expectedTrits, TryteUtil::toTrits((string) $tryte));
        }
    }

    public function testFromTrits()
    {
        foreach (TryteUtil::TRYTE_TO_TRITS_MAP as $expectedTryte => $trits) {
            static::assertEquals($expectedTryte, TryteUtil::fromTrits($trits[0], $trits[1], $trits[2]));
        }
    }
}
