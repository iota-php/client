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

use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle\Request;
use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle\Response;

/**
 * @coversNothing
 */
class InterruptAttachingToTangleTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'interruptAttachingToTangle',
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/InterruptAttachingToTangle.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();
        static::assertInstanceOf(Response::class, $response);
    }

    public function provideResponseMissing()
    {
        return [];
    }

    protected function initValidRequest()
    {
        $this->request = new Request();
    }
}
