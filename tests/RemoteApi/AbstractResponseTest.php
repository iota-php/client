<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\RequestInterface;

class AbstractResponseTest extends TestCase
{
    public function testGetter()
    {
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $node = new Node();
        $response = new class($request) extends AbstractResponse {
            protected function mapResults(): void { }
        };

        static::assertFalse($response->isError());
        static::assertEquals(null, $response->getCode());
        static::assertEquals('{}', $response->getBody());
        static::assertEquals([], $response->getRawData());
        static::assertEquals($node, $response->getNode());
        static::assertEquals($request, $response->getRequest());
    }

    public function testError()
    {
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $node = new Node();
        $response = new class($request) extends AbstractResponse {
            protected function mapResults(): void { }
        };

        static::assertTrue($response->isError());
    }

}