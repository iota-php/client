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
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Trytes;

/**
 * @coversNothing
 */
class AddressTest extends TestCase
{
    public function testValidCreation()
    {
        $address = new Address(str_repeat('A', 81));
        static::assertEquals(str_repeat('A', 81), (string) $address);
        $address = $address->setCheckSum(new Trytes('AAAAAAAAA'));

        static::assertEquals(str_repeat('A', 90), (string) $address);
        $address = new Address(str_repeat('A', 90));
        static::assertEquals(str_repeat('A', 90), (string) $address);
    }

    public function testInvalidCreationTryte()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Address('Ä');
    }

    public function testInvalidCreationTooShort()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Address(str_repeat('A', 80));
    }

    public function testInvalidCreationTooLong()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Address(str_repeat('A', 82));
    }

    public function testIndex()
    {
        $address = new Address(str_repeat('A', 81), 5);
        static::assertEquals(5, $address->getIndex());
        static::assertTrue($address->hasIndex());

        $address = new Address(str_repeat('A', 81));
        static::assertFalse($address->hasIndex());
    }

    public function testChecksum()
    {
        $address = new Address(str_repeat('A', 81), 5);
        static::assertFalse($address->hasChecksum());
        $newAddress = $address->setCheckSum(new Trytes('AAAAAAAAA'));
        static::assertFalse($address->hasChecksum());
        static::assertTrue($newAddress->hasChecksum());

        $remAddress = $address->removeChecksum();
        static::assertEquals(str_repeat('A', 90), (string) $newAddress);
        static::assertEquals(str_repeat('A', 81), (string) $address);
        static::assertEquals(str_repeat('A', 81), (string) $remAddress);
    }

    public function testSerialize()
    {
        $address = new Address(str_repeat('A', 81));
        $s = $address->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertArrayHasKey('index', $s);
        static::assertEquals(-1, $s['index']);
        static::assertArrayHasKey('checkSum', $s);
        static::assertNull($s['checkSum']);

        $address = new Address(str_repeat('A', 90));
        $s = $address->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertArrayHasKey('index', $s);
        static::assertEquals(-1, $s['index']);
        static::assertArrayHasKey('checkSum', $s);
        static::assertEquals('AAAAAAAAA', $s['checkSum']);

        $address = new Address(str_repeat('A', 90), 5);
        $s = $address->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertArrayHasKey('index', $s);
        static::assertEquals(5, $s['index']);
        static::assertArrayHasKey('checkSum', $s);
        static::assertEquals('AAAAAAAAA', $s['checkSum']);
    }
}
