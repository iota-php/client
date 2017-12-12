<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\GetBalances\Request;
use Techworker\IOTA\RemoteApi\Commands\GetBalances\Response;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Milestone;

class GetBalancesTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request([
            new Address($this->generateStaticTryte(81, 0)),
            new Address($this->generateStaticTryte(81, 1))
        ], 99);
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getBalances',
            'addresses' => [
                $this->generateStaticTryte(81, 0),
                $this->generateStaticTryte(81, 1)
            ],
            'threshold' => 99
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidAddresses()
    {
        $request = new Request(['abc']);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/GetBalances.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertEquals([114544444], $response->getBalances());
        static::assertInstanceOf(Milestone::class, $response->getMilestone());
        static::assertEquals('INRTUYSZCWBHGFGGXXPWRWBZACYAFGVRRP9VYEQJOHYD9URMELKWAFYFMNTSP9MCHLXRGAFMBOZPZ9999', (string)$response->getMilestone());
        static::assertEquals(128, $response->getMilestone()->getIndex());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/GetBalances.json', 'balances'],
            [__DIR__ . '/fixtures/GetBalances.json', 'milestone'],
            [__DIR__ . '/fixtures/GetBalances.json', 'milestoneIndex'],
        ];
    }


}