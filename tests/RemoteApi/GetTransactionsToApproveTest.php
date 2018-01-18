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

use Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove\Request;
use Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove\Response;
use Techworker\IOTA\Type\TransactionHash;

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
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetTransactionsToApprove.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

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

    protected function initValidRequest()
    {
        $this->markTestSkipped('TODO');
        $this->request = new Request(1);
    }
}
