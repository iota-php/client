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

namespace IOTA\Tests;

use PHPUnit\Framework\TestCase;
use IOTA\Node;

/**
 * Class NodeTest.
 *
 * @coversNothing
 */
class NodeTest extends TestCase
{
    public function testCreateNodeDefaults()
    {
        $node = new Node();
        static::assertEquals('http://localhost:14265', $node->getHost());
        static::assertNull($node->getToken());
        static::assertFalse($node->doesPOW());
        static::assertEquals(1, $node->getApiVersion());
        static::assertEquals('http://localhost:14265/commands', $node->getCommandsEndpoint());
    }

    public function testCreateNode()
    {
        $node = new Node('http://127.0.0.1:14265', true, 1, 'ABC');
        static::assertEquals('http://127.0.0.1:14265', $node->getHost());
        static::assertEquals(true, $node->doesPOW());
        static::assertEquals(1, $node->getApiVersion());
        static::assertEquals('ABC', $node->getToken());
    }

    public function testSerialize()
    {
        $node = new Node('http://127.0.0.1:14265', true, 1, 'ABC');
        static::assertEquals($node->serialize()['host'], $node->getHost());
        static::assertEquals($node->serialize()['doesPOW'], $node->doesPOW());
        static::assertEquals($node->serialize()['token'], $node->getToken());
        static::assertEquals($node->serialize()['apiVersion'], $node->getApiVersion());
    }
}
