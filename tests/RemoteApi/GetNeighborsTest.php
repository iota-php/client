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
use IOTA\RemoteApi\Actions\GetNeighbors\Action;
use IOTA\RemoteApi\Actions\GetNeighbors\Result;
use IOTA\Type\Neighbor;

/**
 * @coversNothing
 */
class GetNeighborsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getNeighbors',
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();

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
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.0.address'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.0.numberOfAllTransactions'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.0.numberOfInvalidTransactions'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.0.numberOfNewTransactions'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.1.address'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.1.numberOfAllTransactions'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.1.numberOfInvalidTransactions'],
            [__DIR__.'/fixtures/GetNeighbors.json', 'neighbors.1.numberOfNewTransactions'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action($this->httpClient, new Node());
    }
}
