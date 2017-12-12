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

/**
 * Class SerializeUtilTest
 *
 * @package Techworker\IOTA\Tests\Util
 */
class SerializeUtilTest extends TestCase
{
    /**
     * Gets a list of test data.
     *
     * @return array
     */
    public function testSerializeArray()
    {
        $inst1 = new class implements SerializeInterface
        {
            public function serialize()
            {
                return ['A' => 'B'];
            }
        };
        $inst2 = new class implements SerializeInterface
        {
            public function serialize()
            {
                return ['C' => 'D'];
            }
        };

        $s = SerializeUtil::serializeArray(['FIRST' => $inst1, 'SECOND' => $inst2]);
        static::assertArrayHasKey('FIRST', $s);
        static::assertArrayHasKey('SECOND', $s);

        static::assertEquals(['A' => 'B'], $s['FIRST']);
        static::assertEquals(['C' => 'D'], $s['SECOND']);
    }

}