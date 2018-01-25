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
use Techworker\IOTA\Type\Trytes;
use Techworker\IOTA\Util\TryteUtil;

/**
 * @coversNothing
 */
class TrytesTest extends TestCase
{
    public function testConstruct()
    {
        $trytes = new Trytes();
        static::assertEmpty((string) $trytes);

        foreach (array_keys(TryteUtil::TRYTE_TO_TRITS_MAP) as $validTryte) {
            $trytes = new Trytes((string) $validTryte);
            static::assertEquals($validTryte, (string) $trytes);
        }

        for ($i = 0; $i <= 255; ++$i) {
            // skip A-Z / 9
            if (($i >= 65 && $i <= 90) || 57 === $i) {
                continue;
            }

            try {
                new Trytes((string) chr($i));
                static::assertTrue(false);
            } catch (\InvalidArgumentException $ia) {
                static::assertTrue(true);
            }
        }
    }

    public function testInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Trytes('abc');
    }

    public function testIterate()
    {
        $tryteArr = ['A', 'B', 'C'];

        $trytes = new Trytes('ABC');
        foreach ($trytes as $idx => $tryte) {
            static::assertEquals($tryteArr[$idx], $tryte);
        }
    }

    public function testCount()
    {
        $all = '';
        foreach (array_keys(TryteUtil::TRYTE_TO_TRITS_MAP) as $validTryte) {
            $all .= $validTryte;
            $trytes = new Trytes((string) $all);
            static::assertEquals(strlen($all), $trytes->count());
        }
    }

    public function testEquals()
    {
        $tryte1 = new Trytes('ABC');
        $tryte2 = new Trytes('DEF');

        static::assertTrue($tryte1->equals($tryte1));
        static::assertFalse($tryte1->equals($tryte2));
    }

    public function testSerialize()
    {
        $tryte1 = new Trytes('ABC');
        $s = $tryte1->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals('ABC', $s['trytes']);
    }
}
