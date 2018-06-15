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

namespace IOTA\Tests\RemoteApi;

use IOTA\Node;
use IOTA\RemoteApi\Actions\AddNeighbors\Action;
use IOTA\RemoteApi\NodeApiClient;
use PHPUnit\Framework\TestCase;

class AddNeighborsTest extends TestCase
{
    public function testRequestSerialization()
    {
        $action = new Action($this->createMock(NodeApiClient::class), new Node);
        $action->setNeighborUris(['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265']);
        $expected = [
            'command' => 'addNeighbors',
            'uris' => ['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265'],
        ];
        static::assertEquals($expected, $action->jsonSerialize());
    }

    public function testRequestInvalidUri()
    {
        $action = new Action($this->createMock(NodeApiClient::class), new Node);
        $this->expectException(\InvalidArgumentException::class);
        $action->setNeighborUris(['abc']);
    }

    public function testResponse()
    {
        $fixture = file_get_contents(__DIR__.'/fixtures/AddNeighbors.json');

        $client = $this->createMock(NodeApiClient::class);
        $client->expects($this->once())->method('send')->willReturn([
            'code' => 200,
            'raw' => $fixture
        ]);
        $action = new Action($client, new Node);
        $action->addNeighborUri('udp://0.0.0.0:14265');

        $response = $action->execute();
        static::assertEquals(10, $response->getAddedNeighbors());
    }

}
