<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\Util;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Cryptography\Hashing\KerlFactory;
use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Tests\Container;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\AddressUtil;
use Techworker\IOTA\Util\SerializeUtil;
use Techworker\IOTA\Util\TryteUtil;

/**
 * Class TryteUtilTest
 *
 * @package Techworker\IOTA\Tests\Util
 */
class TryteUtilTest extends TestCase
{
    public function testToTrits()
    {
        foreach(TryteUtil::TRYTE_TO_TRITS_MAP as $tryte => $expectedTrits) {
            static::assertEquals($expectedTrits, TryteUtil::toTrits((string)$tryte));
        }
    }

    public function testFromTrits()
    {
        foreach(TryteUtil::TRYTE_TO_TRITS_MAP as $expectedTryte => $trits) {
            static::assertEquals($expectedTryte, TryteUtil::fromTrits($trits[0], $trits[1], $trits[2]));
        }
    }
}