<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Base\Types\Test;

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

}
