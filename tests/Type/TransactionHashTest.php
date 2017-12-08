<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Base\Types\Test;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\TransactionHash;

class TransactionHashTest extends TestCase
{
    public function testValidCreation()
    {
        $ms = new TransactionHash(str_repeat('A', 81));
        static::assertEquals(str_repeat('A', 81), (string)$ms);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTryte()
    {
        new TransactionHash('Ä');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTooShort()
    {
        new TransactionHash(str_repeat('A', 80));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTooLong()
    {
        new TransactionHash(str_repeat('A', 82));
    }

}
