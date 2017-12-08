<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Node;
use Techworker\IOTA\RemoteApi\RequestInterface;

class AbstractResponseTest extends TestCase
{
    public function testGetter()
    {
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $node = new Node();
        $response = new class(200, '{}', $request, $node) extends AbstractResponse {
            protected function mapResults(): void { }
        };

        static::assertFalse($response->isError());
        static::assertEquals(200, $response->getCode());
        static::assertEquals('{}', $response->getBody());
        static::assertEquals([], $response->getRawData());
        static::assertEquals($node, $response->getNode());
        static::assertEquals($request, $response->getRequest());
    }

    public function testError()
    {
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $node = new Node();
        $response = new class(404, '{}', $request, $node) extends AbstractResponse {
            protected function mapResults(): void { }
        };

        static::assertTrue($response->isError());
    }

}