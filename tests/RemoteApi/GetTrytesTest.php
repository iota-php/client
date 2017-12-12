<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\GetTrytes\Request;
use Techworker\IOTA\RemoteApi\Commands\GetTrytes\Response;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Type\Trytes;

class GetTrytesTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request(
            new TransactionHash($this->generateStaticTryte(81, 0)),
            new TransactionHash($this->generateStaticTryte(81, 1))
        );
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getTrytes',
            'hashes' => [
                $this->generateStaticTryte(81, 0),
                $this->generateStaticTryte(81, 1)
            ]
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/GetTrytes.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertCount(2, $response->getTransactions());
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[0]);
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[1]);
        static::assertEquals('ABC', (string)$response->getTransactions()[0]);
        static::assertEquals('DEF', (string)$response->getTransactions()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/GetTransactionsToApprove.json', 'trytes']
        ];
    }
}