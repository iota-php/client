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

use IOTA\RemoteApi\Actions\FindTransactions\Action;
use IOTA\RemoteApi\Actions\FindTransactions\Result;
use IOTA\Type\Address;
use IOTA\Type\Approvee;
use IOTA\Type\BundleHash;
use IOTA\Type\Tag;
use IOTA\Type\TransactionHash;

/**
 * @coversNothing
 */
class FindTransactionsTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'findTransactions',
            'bundles' => [$this->generateStaticTryte(3, 0).str_repeat('9', 78), $this->generateStaticTryte(3, 1).str_repeat('9', 78)],
            'addresses' => [$this->generateStaticTryte(81, 2), $this->generateStaticTryte(81, 3)],
            'tags' => [$this->generateStaticTryte(3, 4), $this->generateStaticTryte(3, 5)],
            'approvees' => [$this->generateStaticTryte(3, 6), $this->generateStaticTryte(3, 7)],
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testRequestSerializationOnlyBundles()
    {
        $expected = [
            'command' => 'findTransactions',
            'bundles' => [$this->generateStaticTryte(81, 0)],
        ];
        $request = new Action(
            [new BundleHash($this->generateStaticTryte(81, 0))]
        );
        static::assertEquals($expected, $request->jsonSerialize());
    }

    public function testRequestSerializationOnlyAddresses()
    {
        $expected = [
            'command' => 'findTransactions',
            'addresses' => [$this->generateStaticTryte(81, 0)],
        ];
        $request = new Action(
            [],
            [new Address($this->generateStaticTryte(81, 0))]
        );
        static::assertEquals($expected, $request->jsonSerialize());
    }

    public function testRequestSerializationOnlyTags()
    {
        $expected = [
            'command' => 'findTransactions',
            'tags' => [$this->generateStaticTryte(81, 0)],
        ];

        $request = new Action(
            [],
            [],
            [new Tag($this->generateStaticTryte(81, 0))]
        );
        static::assertEquals($expected, $request->jsonSerialize());
    }

    public function testRequestSerializationOnlyApprovees()
    {
        $expected = [
            'command' => 'findTransactions',
            'approvees' => [$this->generateStaticTryte(81, 0)],
        ];
        $request = new Action(
            [],
            [],
            [],
            [new Approvee($this->generateStaticTryte(81, 0))]
        );
        static::assertEquals($expected, $request->jsonSerialize());
    }

    public function testRequestInvalidBundle()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Action(['test']);
    }

    public function testRequestInvalidAddress()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Action([], ['test']);
    }

    public function testRequestInvalidTags()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Action([], [], ['test']);
    }

    public function testRequestInvalidApprovees()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Action([], [], [], ['test']);
    }

    public function testResponse()
    {
        $this->markTestSkipped('TODO');
        $fixture = $this->loadFixture(__DIR__.'/fixtures/FindTransactions.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->action, new Node());

        static::assertCount(2, $response->getTransactionHashes());
        static::assertInstanceOf(TransactionHash::class, $response->getTransactionHashes()[0]);
        static::assertInstanceOf(TransactionHash::class, $response->getTransactionHashes()[1]);
        static::assertEquals('ZJVYUGTDRPDYFGFXMKOTV9ZWSGFK9CFPXTITQLQNLPPG9YNAARMKNKYQO9GSCSBIOTGMLJUFLZWSY9999', (string) $response->getTransactionHashes()[0]);
        static::assertEquals('ZJVYUGTDRPDYFGFXMKOTV9ZWSGFK9CFPXTITQLQNLPPG9YNAARMKNKYQO9GSCSBIOTGMLJUFLZWSY999A', (string) $response->getTransactionHashes()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/FindTransactions.json', 'hashes'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $bundles = [
            new BundleHash($this->generateStaticTryte(3, 0)),
            new BundleHash($this->generateStaticTryte(3, 1)),
        ];
        $addresses = [
            new Address($this->generateStaticTryte(81, 2)),
            new Address($this->generateStaticTryte(81, 3)),
        ];
        $tags = [
            new Tag($this->generateStaticTryte(3, 4)),
            new Tag($this->generateStaticTryte(3, 5)),
        ];
        $approvees = [
            new Approvee($this->generateStaticTryte(3, 6)),
            new Approvee($this->generateStaticTryte(3, 7)),
        ];

        $this->action = new Action(
            $bundles,
            $addresses,
            $tags,
            $approvees
        );
    }
}
