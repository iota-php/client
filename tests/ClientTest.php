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
use IOTA\ClientApi\ClientApi;
use IOTA\Client;
use IOTA\Node;
use IOTA\RemoteApi\RemoteApi;

/**
 * Class IOTATest.
 */
class ClientTest extends TestCase
{
    public function testGetNodes()
    {
        $nodes = [
            new Node('http://127.0.0.1:14265'),
            new Node('http://127.0.0.2:14265'),
            'mynode' => new Node('http://myNode'),
        ];

        $client = $this->getClientInstance($nodes);
        static::assertCount(3, $client->getNodes());
    }

    public function testGetNode()
    {
        $nodes = [
            new Node('http://127.0.0.1:14265'),
            new Node('http://127.0.0.2:14265'),
            'mynode' => new Node('http://myNode'),
        ];

        $client = $this->getClientInstance($nodes);

        // without key
        $node = $client->getNode();
        static::assertInstanceOf(Node::class, $node);
        static::assertEquals($node, $client->getLastUsedNode());

        $node = $client->getNode('mynode');
        static::assertEquals($node->getHost(), 'http://myNode');
    }

    public function testInvalidNode()
    {
        $this->expectException(\Exception::class);

        $iota = $this->getClientInstance();
        $iota->getNode('-1');
    }

    public function testGetRemoteApi()
    {
        $client = $this->getClientInstance([]);
        static::assertInstanceOf(RemoteApi::class, $client->getRemoteApi());
    }

    public function testGetClientApi()
    {
        $client = $this->getClientInstance([]);
        static::assertInstanceOf(ClientApi::class, $client->getClientApi());
    }

    /**
     * Creates a new iota cloent instance.
     *
     * @param Node[] $nodes
     *
     * @return Client
     */
    private function getClientInstance(array $nodes = []): Client
    {
        return new Client(
            $this->prophesize(RemoteApi::class)->reveal(),
            $this->prophesize(ClientApi::class)->reveal(),
            $nodes
        );
    }
}
