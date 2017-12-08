<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\GetTips\Request;
use Techworker\IOTA\RemoteApi\Commands\GetTips\Response;
use Techworker\IOTA\RemoteApi\Node;
use Techworker\IOTA\Type\Tip;

class GetTipsTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $this->request = new Request();
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getTips'
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/GetTips.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->request, new Node());

        static::assertCount(2, $response->getHashes());
        static::assertInstanceOf(Tip::class, $response->getHashes()[0]);
        static::assertInstanceOf(Tip::class, $response->getHashes()[1]);
        static::assertEquals('YVXJOEOP9JEPRQUVBPJMB9MGIB9OMTIJJLIUYPM9YBIWXPZ9PQCCGXYSLKQWKHBRVA9AKKKXXMXF99999', (string)$response->getHashes()[0]);
        static::assertEquals('ZUMARCWKZOZRMJM9EEYJQCGXLHWXPRTMNWPBRCAGSGQNRHKGRUCIYQDAEUUEBRDBNBYHAQSSFZZQW9999', (string)$response->getHashes()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/GetTips.json', 'hashes'],
        ];
    }
}