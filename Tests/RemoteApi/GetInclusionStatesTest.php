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

use Techworker\IOTA\RemoteApi\Actions\GetInclusionStates\Action;
use Techworker\IOTA\RemoteApi\Actions\GetInclusionStates\Result;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Type\TransactionHash;

/**
 * @coversNothing
 */
class GetInclusionStatesTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getInclusionStates',
            'transactions' => [
                $this->generateStaticTryte(81, 0),
                $this->generateStaticTryte(81, 1),
            ],
            'tips' => [
                $this->generateStaticTryte(3, 0),
                $this->generateStaticTryte(3, 1),
            ],
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testRequestInvalidTransaction()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Action(['abc'], []);
    }

    public function testRequestInvalidTip()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Action([], ['abc']);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetInclusionStates.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();

        static::assertTrue($response->getStates()[0]);
        static::assertFalse($response->getStates()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/GetInclusionStates.json', 'states'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action([
            new TransactionHash($this->generateStaticTryte(81, 0)),
            new TransactionHash($this->generateStaticTryte(81, 1)),
        ], [
            new Tip($this->generateStaticTryte(3, 0)),
            new Tip($this->generateStaticTryte(3, 1)),
        ]);
    }
}
