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

use Techworker\IOTA\RemoteApi\Commands\GetTips\Request;
use Techworker\IOTA\RemoteApi\Commands\GetTips\Response;
use Techworker\IOTA\Type\Tip;

/**
 * @coversNothing
 */
class GetTipsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getTips',
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetTips.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertCount(2, $response->getHashes());
        static::assertInstanceOf(Tip::class, $response->getHashes()[0]);
        static::assertInstanceOf(Tip::class, $response->getHashes()[1]);
        static::assertEquals('YVXJOEOP9JEPRQUVBPJMB9MGIB9OMTIJJLIUYPM9YBIWXPZ9PQCCGXYSLKQWKHBRVA9AKKKXXMXF99999', (string) $response->getHashes()[0]);
        static::assertEquals('ZUMARCWKZOZRMJM9EEYJQCGXLHWXPRTMNWPBRCAGSGQNRHKGRUCIYQDAEUUEBRDBNBYHAQSSFZZQW9999', (string) $response->getHashes()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/GetTips.json', 'hashes'],
        ];
    }

    protected function initValidRequest()
    {
        $this->markTestSkipped('TODO');
        $this->request = new Request($this->httpClient, new Node());
    }
}
