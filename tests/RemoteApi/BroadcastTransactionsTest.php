<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions\Request;
use Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions\Response;
use Techworker\IOTA\Type\Trytes;

class BroadcastTransactionsTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request(
            new Trytes($this->generateStaticTryte(3, 0)),
            new Trytes($this->generateStaticTryte(3, 1))
        );
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'broadcastTransactions',
            'trytes' => [
                $this->generateStaticTryte(3, 0),
                $this->generateStaticTryte(3, 1)
            ]
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/FindTransactions.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertInstanceOf(Response::class, $response);
    }

    public function provideResponseMissing()
    {
        return [];
    }

}