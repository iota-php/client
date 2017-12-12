<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;

use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Trytes;

class AddressTest extends TestCase
{
    public function testValidCreation()
    {
        $address = new Address(str_repeat('A', 81));
        static::assertEquals(str_repeat('A', 81), (string)$address);
        $address->setChecksum(new Trytes('AAAAAAAAA'));

        static::assertEquals(str_repeat('A', 90), (string)$address);
        $address = new Address(str_repeat('A', 90));
        static::assertEquals(str_repeat('A', 90), (string)$address);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTryte()
    {
        new Address('Ä');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTooShort()
    {
        new Address(str_repeat('A', 80));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTooLong()
    {
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
        $address->setChecksum(new Trytes('AAAAAAAAA'));
        static::assertTrue($address->hasChecksum());

        static::assertEquals(str_repeat('A', 90), (string)$address);
        $address->removeChecksum();
        static::assertEquals(str_repeat('A', 81), (string)$address);
    }

    public function testSerialize()
    {
        $address = new Address(str_repeat('A', 81));
        $s = $address->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertArrayHasKey('index', $s);
        static::assertEquals(-1, $s['index']);
        static::assertArrayHasKey('checksum', $s);
        static::assertNull($s['checksum']);

        $address = new Address(str_repeat('A', 90));
        $s = $address->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertArrayHasKey('index', $s);
        static::assertEquals(-1,$s['index']);
        static::assertArrayHasKey('checksum', $s);
        static::assertEquals('AAAAAAAAA', $s['checksum']);

        $address = new Address(str_repeat('A', 90), 5);
        $s = $address->serialize();
        static::assertArrayHasKey('trytes', $s);
        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertArrayHasKey('index', $s);
        static::assertEquals(5,$s['index']);
        static::assertArrayHasKey('checksum', $s);
        static::assertEquals('AAAAAAAAA', $s['checksum']);

    }
}
