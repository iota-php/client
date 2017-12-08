<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Test\Util;

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