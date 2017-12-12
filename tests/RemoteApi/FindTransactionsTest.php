<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\FindTransactions\Request;
use Techworker\IOTA\RemoteApi\Commands\FindTransactions\Response;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Approvee;
use Techworker\IOTA\Type\BundleHash;
use Techworker\IOTA\Type\Tag;
use Techworker\IOTA\Type\TransactionHash;

class FindTransactionsTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        $bundles = [
            new BundleHash($this->generateStaticTryte(3, 0)),
            new BundleHash($this->generateStaticTryte(3, 1))
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

        $this->request = new Request(
            $bundles, $addresses, $tags, $approvees
        );
    }

    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'findTransactions',
            'bundles' => [$this->generateStaticTryte(3, 0) . str_repeat('9', 78), $this->generateStaticTryte(3, 1) . str_repeat('9', 78)],
            'addresses' => [$this->generateStaticTryte(81, 2), $this->generateStaticTryte(81, 3)],
            'tags' => [$this->generateStaticTryte(3, 4), $this->generateStaticTryte(3, 5)],
            'approvees' => [$this->generateStaticTryte(3, 6), $this->generateStaticTryte(3, 7)]
        ];
        static::assertEquals($expected, $this->request->jsonSerialize());
    }

    public function testRequestSerializationOnlyBundles()
    {
        $expected = [
            'command' => 'findTransactions',
            'bundles' => [$this->generateStaticTryte(81, 0)],
        ];
        $request = new Request(
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
        $request = new Request([],
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

        $request = new Request([], [],
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
        $request = new Request([], [], [],
            [new Approvee($this->generateStaticTryte(81, 0))]
        );
        static::assertEquals($expected, $request->jsonSerialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidBundle()
    {
        new Request(['test']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidAddress()
    {
        new Request([], ['test']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidTags()
    {
        new Request([], [], ['test']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestInvalidApprovees()
    {
        new Request([], [], [], ['test']);
    }


    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/FindTransactions.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->httpClient->commandRequest($this->request, new Node());

        static::assertCount(2, $response->getTransactionHashes());
        static::assertInstanceOf(TransactionHash::class, $response->getTransactionHashes()[0]);
        static::assertInstanceOf(TransactionHash::class, $response->getTransactionHashes()[1]);
        static::assertEquals('ZJVYUGTDRPDYFGFXMKOTV9ZWSGFK9CFPXTITQLQNLPPG9YNAARMKNKYQO9GSCSBIOTGMLJUFLZWSY9999', (string)$response->getTransactionHashes()[0]);
        static::assertEquals('ZJVYUGTDRPDYFGFXMKOTV9ZWSGFK9CFPXTITQLQNLPPG9YNAARMKNKYQO9GSCSBIOTGMLJUFLZWSY999A', (string)$response->getTransactionHashes()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/FindTransactions.json', 'hashes']
        ];
    }

}