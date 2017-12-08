<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\GetNeighbors\Neighbor;
use Techworker\IOTA\RemoteApi\Commands\GetNeighbors\Request;
use Techworker\IOTA\RemoteApi\Commands\GetNeighbors\Response;
use Techworker\IOTA\RemoteApi\Node;

class GetNeighborsTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request();
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getNeighbors'
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/GetNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->request, new Node());

        static::assertInstanceOf(Neighbor::class, $response->getNeighbors()[0]);
        static::assertInstanceOf(Neighbor::class, $response->getNeighbors()[1]);

        static::assertEquals('/8.8.8.8:14265', $response->getNeighbors()[0]->getAddress());
        static::assertEquals(922, $response->getNeighbors()[0]->getNumberOfAllTransactions());
        static::assertEquals(2, $response->getNeighbors()[0]->getNumberOfInvalidTransactions());
        static::assertEquals(92, $response->getNeighbors()[0]->getNumberOfNewTransactions());

        static::assertEquals('/8.8.8.8:5000', $response->getNeighbors()[1]->getAddress());
        static::assertEquals(925, $response->getNeighbors()[1]->getNumberOfAllTransactions());
        static::assertEquals(14, $response->getNeighbors()[1]->getNumberOfInvalidTransactions());
        static::assertEquals(20, $response->getNeighbors()[1]->getNumberOfNewTransactions());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.0.address'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.0.numberOfAllTransactions'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.0.numberOfInvalidTransactions'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.0.numberOfNewTransactions'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.1.address'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.1.numberOfAllTransactions'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.1.numberOfInvalidTransactions'],
            [__DIR__ . '/fixtures/GetNeighbors.json', 'neighbors.1.numberOfNewTransactions'],
        ];
    }
}