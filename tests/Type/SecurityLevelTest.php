<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\SecurityLevel;

class SecurityLevelTest extends TestCase
{
    public function testInit()
    {
        static::assertEquals(1, SecurityLevel::LEVEL_1()->getLevel());
        static::assertEquals(2, SecurityLevel::LEVEL_2()->getLevel());
        static::assertEquals(3, SecurityLevel::LEVEL_3()->getLevel());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalid()
    {
        new SecurityLevel(4);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalid2()
    {
        new SecurityLevel(0);
    }

    public function testFromValue()
    {
        $s1 = SecurityLevel::fromValue(1);
        static::assertInstanceOf(SecurityLevel::class, $s1);
        static::assertEquals(1, $s1->getLevel());

        $s2 = SecurityLevel::fromValue(2);
        static::assertInstanceOf(SecurityLevel::class, $s2);
        static::assertEquals(2, $s2->getLevel());

        $s3 = SecurityLevel::fromValue(3);
        static::assertInstanceOf(SecurityLevel::class, $s3);
        static::assertEquals(3, $s3->getLevel());

        $s4 = SecurityLevel::fromValue(4);
        static::assertNull($s4);
    }

    public function testSerialize()
    {
        $s1 = SecurityLevel::LEVEL_1();
        $s = $s1->serialize();
        static::assertEquals(1, $s);

        $s2 = SecurityLevel::LEVEL_2();
        $s = $s2->serialize();
        static::assertEquals(2, $s);

        $s3 = SecurityLevel::LEVEL_3();
        $s = $s3->serialize();
        static::assertEquals(3, $s);
    }
}