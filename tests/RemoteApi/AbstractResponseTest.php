<?php

declare(strict_types=1);

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Techworker\IOTA\Tests\RemoteApi;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\RequestInterface;

/**
 * @coversNothing
 */
class AbstractResponseTest extends TestCase
{
    public function testGetter()
    {
        $this->markTestSkipped('TODO');
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $node = new Node();
        $response = new class($request) extends AbstractResponse {
            protected function mapResults(): void
            {
            }
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
        $this->markTestSkipped('TODO');
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $node = new Node();
        $response = new class($request) extends AbstractResponse {
            protected function mapResults(): void
            {
            }
        };

        static::assertTrue($response->isError());
    }
}
