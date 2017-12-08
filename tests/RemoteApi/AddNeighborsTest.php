<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\AddNeighbors\Request;
use Techworker\IOTA\RemoteApi\Commands\AddNeighbors\Response;
use Techworker\IOTA\RemoteApi\Node;

class AddNeighborsTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request('udp://0.0.0.0:14265', 'udp://1.1.1.1:14265');
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'addNeighbors',
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
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/AddNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest(new Request('udp://0.0.0.0:14265'), new Node());

        static::assertEquals(10, $response->getAddedNeighbors());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/AddNeighbors.json', 'addedNeighbors']
        ];
    }
}