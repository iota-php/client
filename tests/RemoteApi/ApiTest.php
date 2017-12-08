<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\RemoteApi;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
use Techworker\IOTA\RemoteApi\Node;
use Techworker\IOTA\RemoteApi\RequestInterface;

class ApiTest extends TestCase
{
    public function testpostRequest()
    {
        $node = new Node();
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();

        $client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $client->expects($this->once())
            ->method('postRequest')
            ->with($request, $node)
            ->willReturn(new class(200, '{}', $request, $node) extends AbstractResponse {
                protected function mapResults(): void { }
            });

        $api = new RemoteApi($node, $client);
        $api->callCommand($node, $request);
    }
}