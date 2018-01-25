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

namespace IOTA\Tests\RemoteApi;

use IOTA\RemoteApi\Actions\RemoveNeighbors\Action;
use IOTA\RemoteApi\Actions\RemoveNeighbors\Result;

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
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testRequestInvalidUri()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Action('abc');
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/RemoveNeighbors.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();

        static::assertEquals(10, $response->getRemovedNeighbors());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/RemoveNeighbors.json', 'removedNeighbors'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action('udp://0.0.0.0:14265', 'udp://1.1.1.1:14265');
    }
}
