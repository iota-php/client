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

namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\Seed;

/**
 * @coversNothing
 */
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

    public function testSeedMinLength()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Seed(str_repeat('A', 80));
    }

    public function testSeedToStringEmpty()
    {
        $seed = new Seed(str_repeat('A', 81));
        static::assertEmpty((string) $seed);
        static::assertEmpty($seed->__toString());
    }

    public function testSeedWithCheckSum()
    {
        $seed = new Seed(str_repeat('A', 84));
        static::assertEquals('AAA', (string) $seed->getCheckSum());
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
