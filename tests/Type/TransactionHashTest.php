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

namespace Techworker\IOTA\Base\Types\Test;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\TransactionHash;

/**
 * @coversNothing
 */
class TransactionHashTest extends TestCase
{
    public function testValidCreation()
    {
        $ms = new TransactionHash(str_repeat('A', 81));
        static::assertEquals(str_repeat('A', 81), (string) $ms);
    }

    public function testInvalidCreationTryte()
    {
        $this->expectException(\InvalidArgumentException::class);

        new TransactionHash('Ä');
    }

    public function testInvalidCreationTooShort()
    {
        $this->expectException(\InvalidArgumentException::class);

        new TransactionHash(str_repeat('A', 80));
    }

    public function testInvalidCreationTooLong()
    {
        $this->expectException(\InvalidArgumentException::class);

        new TransactionHash(str_repeat('A', 82));
    }
}
