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

use Techworker\IOTA\RemoteApi\Actions\StoreTransactions\Action;
use Techworker\IOTA\RemoteApi\Actions\StoreTransactions\Result;
use Techworker\IOTA\Type\Trytes;

/**
 * @coversNothing
 */
class StoreTransactionsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'storeTransactions',
            'trytes' => [
                $this->generateStaticTryte(3, 0),
                $this->generateStaticTryte(3, 1),
            ],
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/StoreTransactions.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();
        static::assertInstanceOf(Response::class, $response);
    }

    public function provideResponseMissing()
    {
        return [];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action(
            new Trytes($this->generateStaticTryte(3, 0)),
            new Trytes($this->generateStaticTryte(3, 1))
        );
    }
}
