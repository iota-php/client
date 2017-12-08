<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Test\RemoteApi;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\RemoteApi\Node;

class NodeTest extends TestCase
{
    public function testCreateNodeDefaults()
    {
        $node = new Node();
        static::assertEquals('http://localhost:14265', $node->getHost());
        static::assertEquals(false, $node->isSandbox());
        static::assertNull($node->getToken());
        static::assertEquals(1, $node->getApiVersion());
    }

    public function testCreateNode()
    {
        $node = new Node('http://127.0.0.1:14265', true, 'ABC', 2);
        static::assertEquals('http://127.0.0.1:14265', $node->getHost());
        static::assertEquals(true, $node->isSandbox());
        static::assertEquals('ABC', $node->getToken());
        static::assertEquals(2, $node->getApiVersion());
    }

    public function testGetCommandsEndpoint()
    {
        $node = new Node('http://127.0.0.1:14265', true, 'ABC', 2);
        static::assertEquals('http://127.0.0.1:14265/commands', $node->getCommandsEndpoint());
    }
}