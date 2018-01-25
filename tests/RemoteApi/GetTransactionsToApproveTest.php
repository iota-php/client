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

use IOTA\RemoteApi\Actions\GetTransactionsToApprove\Action;
use IOTA\RemoteApi\Actions\GetTransactionsToApprove\Result;
use IOTA\Type\TransactionHash;

/**
 * @coversNothing
 */
class GetTransactionsToApproveTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getTransactionsToApprove',
            'depth' => 1,
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetTransactionsToApprove.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();

        static::assertInstanceOf(TransactionHash::class, $response->getTrunkTransaction());
        static::assertInstanceOf(TransactionHash::class, $response->getBranchTransaction());
        static::assertEquals('TKGDZ9GEI9CPNQGHEATIISAKYPPPSXVCXBSR9EIWCTHHSSEQCD9YLDPEXYERCNJVASRGWMAVKFQTC9999', (string) $response->getTrunkTransaction());
        static::assertEquals('AAGDZ9GEI9CPNQGHEATIISAKYPPPSXVCXBSR9EIWCTHHSSEQCD9YLDPEXYERCNJVASRGWMAVKFQTC9999', (string) $response->getBranchTransaction());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/GetTransactionsToApprove.json', 'branchTransaction'],
            [__DIR__.'/fixtures/GetTransactionsToApprove.json', 'trunkTransaction'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action(1);
    }
}
