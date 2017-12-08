<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\GetNodeInfo\Request;
use Techworker\IOTA\RemoteApi\Commands\GetNodeInfo\Response;
use Techworker\IOTA\RemoteApi\Node;

class GetNodeInfoTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request();
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getNodeInfo'
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/GetNodeInfo.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->request, new Node());

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals('IRI', $response->getAppName());
        static::assertEquals('1.0.8.nu', $response->getAppVersion());
        static::assertEquals(4, $response->getJreAvailableProcessors());
        static::assertEquals(91707424, $response->getJreFreeMemory());
        static::assertEquals(1908932608, $response->getJreMaxMemory());
        static::assertEquals(122683392, $response->getJreTotalMemory());
        static::assertEquals('VBVEUQYE99LFWHDZRFKTGFHYGDFEAMAEBGUBTTJRFKHCFBRTXFAJQ9XIUEZQCJOQTZNOOHKUQIKOY9999', (string)$response->getLatestMilestone());
        static::assertEquals(107, $response->getLatestMilestone()->getIndex());
        static::assertEquals('ABVEUQYE99LFWHDZRFKTGFHYGDFEAMAEBGUBTTJRFKHCFBRTXFAJQ9XIUEZQCJOQTZNOOHKUQIKOY9999', (string)$response->getLatestSolidSubtangleMilestone());
        static::assertEquals(108, $response->getLatestSolidSubtangleMilestone()->getIndex());
        static::assertEquals(2, $response->getNeighbors());
        static::assertEquals(3, $response->getPacketQueueSize());
        static::assertEquals(1477037811737, $response->getTime());
        static::assertEquals(3, $response->getTips());
        static::assertEquals(7, $response->getTransactionsToRequest());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'appName'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'appVersion'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'jreAvailableProcessors'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'jreFreeMemory'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'jreMaxMemory'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'jreTotalMemory'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'latestMilestone'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'latestMilestoneIndex'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'latestSolidSubtangleMilestone'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'latestSolidSubtangleMilestoneIndex'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'neighbors'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'packetQueueSize'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'time'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'tips'],
            [__DIR__ . '/fixtures/GetNodeInfo.json', 'transactionsToRequest']
        ];
    }
}