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
use IOTA\RemoteApi\Actions\AddNeighbors\Result;

class AddNeighborsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $this->action->setNeighborUris(['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265']);
        $expected = [
            'command' => 'addNeighbors',
            'uris' => ['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265'],
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testRequestInvalidUri()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Action($this->httpClient, new Node());
        $request->setNeighborUris(['abc']);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/AddNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $request = new Action($this->httpClient, new Node());
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

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action($this->httpClient, new Node());
    }
}
