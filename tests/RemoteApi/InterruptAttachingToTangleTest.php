<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle\Request;
use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle\Response;

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