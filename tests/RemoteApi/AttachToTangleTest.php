<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\AttachToTangle\Request;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle\Response;
use Techworker\IOTA\RemoteApi\Node;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Type\Trytes;

class AttachToTangleTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request(
            new TransactionHash($this->generateStaticTryte(81, 0)),
            new TransactionHash($this->generateStaticTryte(81, 1)),
            18,
            new Trytes('GHI'), new Trytes('JKL')
        );
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'attachToTangle',
            'trunkTransaction' => $this->generateStaticTryte(81, 0),
            'branchTransaction' => $this->generateStaticTryte(81, 1),
            'minWeightMagnitude' => 18,
            'trytes' => ['GHI', 'JKL'],
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/AttachToTangle.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->request, new Node());

        static::assertCount(2, $response->getTransactions());
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[0]);
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[1]);
        static::assertEquals('TRYTEVALUEHERE', (string)$response->getTransactions()[0]);
        static::assertEquals('TRYTEVALUEHEREBBB', (string)$response->getTransactions()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/AddNeighbors.json', 'trytes']
        ];
    }

}