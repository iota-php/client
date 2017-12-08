<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle\Request;
use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle\Response;
use Techworker\IOTA\RemoteApi\Node;

class InterruptAttachingToTangleTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request();
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'interruptAttachingToTangle'
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/InterruptAttachingToTangle.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->request, new Node());
        static::assertInstanceOf(Response::class, $response);
    }

    public function provideResponseMissing()
    {
        return [];
    }

}