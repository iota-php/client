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

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\AddNeighbors\Request;
use Techworker\IOTA\RemoteApi\Commands\AddNeighbors\Response;

/**
 * @coversNothing
 */
class AddNeighborsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $this->request->setNeighborUris(['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265']);
        $expected = [
            'command' => 'addNeighbors',
            'uris' => ['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265'],
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testRequestInvalidUri()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Request($this->httpClient, new Node());
        $request->setNeighborUris(['abc']);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/AddNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $request = new Request($this->httpClient, new Node());
        $request->addNeighborUri('udp://0.0.0.0:14265');

        $response = $request->execute();

        static::assertEquals(10, $response->getAddedNeighbors());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/AddNeighbors.json', 'addedNeighbors'],
        ];
    }

    protected function initValidRequest()
    {
        $this->request = new Request($this->httpClient, new Node());
    }
}
