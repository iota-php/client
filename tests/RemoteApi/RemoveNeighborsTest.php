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

use Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors\Request;
use Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors\Response;

/**
 * @coversNothing
 */
class RemoveNeighborsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'removeNeighbors',
            'uris' => ['udp://0.0.0.0:14265', 'udp://1.1.1.1:14265'],
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testRequestInvalidUri()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Request('abc');
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/RemoveNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertEquals(10, $response->getRemovedNeighbors());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/RemoveNeighbors.json', 'removedNeighbors'],
        ];
    }

    protected function initValidRequest()
    {
        $this->request = new Request('udp://0.0.0.0:14265', 'udp://1.1.1.1:14265');
    }
}
