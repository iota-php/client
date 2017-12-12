<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors\Request;
use Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors\Response;

class RemoveNeighborsTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request('udp://0.0.0.0:14265', 'udp://1.1.1.1:14265');
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'removeNeighbors',
            'uris' => ['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265']
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidUri()
    {
        $request = new Request('abc');
    }


    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/RemoveNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertEquals(10, $response->getRemovedNeighbors());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/RemoveNeighbors.json', 'removedNeighbors']
        ];
    }
}