<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\Tests;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\ClientApi\ClientApi;
use Techworker\IOTA\Exception;
use Techworker\IOTA\IOTA;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\RemoteApi;

/**
 * Class IOTATest
 *
 * @package Techworker\IOTA\Tests
 */
class IOTATest extends TestCase
{
    public function testGetNodes()
    {
        $nodes = [
            new Node('http://127.0.0.1:14265'),
            new Node('http://127.0.0.2:14265'),
            'mynode' => new Node('http://myNode')
        ];

        $iota = new IOTA(new Container(), $nodes);

        static::assertCount(3, $iota->getNodes());

        // without key
        $node = $iota->getNode();
        static::assertInstanceOf(Node::class, $node);
        static::assertEquals($node, $iota->getLastUsedNode());

        $node = $iota->getNode('mynode');
        static::assertEquals($node->getHost(), 'http://myNode');
    }

    /**
     * @expectedException Exception
     */
    public function testInvalidNode()
    {
        $nodes = [];
        $iota = new IOTA(new Container(), $nodes);
        $iota->getNode('-1');
    }

    public function testGetRemoteApi()
    {
        $iota = new IOTA(new Container(), []);
        static::assertInstanceOf(RemoteApi::class, $iota->getRemoteApi());
    }

    public function testGetClientApi()
    {
        $iota = new IOTA(new Container(), []);
        static::assertInstanceOf(ClientApi::class, $iota->getClientApi());
    }
}