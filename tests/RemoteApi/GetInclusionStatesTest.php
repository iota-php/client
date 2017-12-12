<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\GetInclusionStates\Request;
use Techworker\IOTA\RemoteApi\Commands\GetInclusionStates\Response;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Type\TransactionHash;

class GetInclusionStatesTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request([
            new TransactionHash($this->generateStaticTryte(81, 0)),
            new TransactionHash($this->generateStaticTryte(81, 1))
        ], [
            new Tip($this->generateStaticTryte(3, 0)),
            new Tip($this->generateStaticTryte(3, 1))
        ]);
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getInclusionStates',
            'transactions' => [
                $this->generateStaticTryte(81, 0),
                $this->generateStaticTryte(81, 1)
            ],
            'tips' => [
                $this->generateStaticTryte(3, 0),
                $this->generateStaticTryte(3, 1)
            ]
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidTransaction()
    {
        $request = new Request(['abc'], []);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidTip()
    {
        $request = new Request([], ['abc']);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/GetInclusionStates.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertTrue($response->getStates()[0]);
        static::assertFalse($response->getStates()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/GetInclusionStates.json', 'states'],
        ];
    }


}