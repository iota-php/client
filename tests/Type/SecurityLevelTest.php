<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Test\Type;

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
}