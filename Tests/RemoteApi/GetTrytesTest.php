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

use Techworker\IOTA\RemoteApi\Actions\GetTrytes\Action;
use Techworker\IOTA\RemoteApi\Actions\GetTrytes\Result;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Type\Trytes;

/**
 * @coversNothing
 */
class GetTrytesTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getTrytes',
            'hashes' => [
                $this->generateStaticTryte(81, 0),
                $this->generateStaticTryte(81, 1),
            ],
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetTrytes.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();

        static::assertCount(2, $response->getTransactions());
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[0]);
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[1]);
        static::assertEquals('ABC', (string) $response->getTransactions()[0]);
        static::assertEquals('DEF', (string) $response->getTransactions()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/GetTransactionsToApprove.json', 'trytes'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action(
            new TransactionHash($this->generateStaticTryte(81, 0)),
            new TransactionHash($this->generateStaticTryte(81, 1))
        );
    }
}
