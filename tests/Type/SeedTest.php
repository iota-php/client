<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\Seed;

class SeedTest extends TestCase
{
    public function testValidCreation()
    {
        $instance = new Seed(str_repeat('A', 81));
        static::assertEquals(str_repeat('A', 81), $instance->getSeed());
    }

    public function testLength()
    {
        $seed = new Seed(str_repeat('A', 81));
        static::assertEquals(81, $seed->count());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSeedMinLength()
    {
        new Seed(str_repeat('A', 80));
    }

    public function testSeedToStringEmpty()
    {
        $seed = new Seed(str_repeat('A', 81));
        static::assertEmpty((string)$seed);
        static::assertEmpty($seed->__toString());
    }

    public function testSeedIsNotInDump()
    {
        $seed = new Seed(str_repeat('A', 81));
        $printed = print_r($seed, true);
        ob_start();
        var_dump($seed, true);
        $dumped = ob_get_contents();
        ob_end_clean();
        static::assertEquals(0, substr_count($printed, 'AAA'));
        static::assertEquals(0, substr_count($dumped, 'AAA'));
    }

    public function testIsNotSerializable()
    {
        $seed = new Seed(str_repeat('A', 81));
        static::assertEquals(0, substr_count(serialize($seed), 'AAA'));
    }
}
