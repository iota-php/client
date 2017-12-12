<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Cryptography\POW\PowInterface;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle\Request;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle\Response;
use Techworker\IOTA\Tests\Container;
use Techworker\IOTA\Tests\DummyData;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Type\Trytes;

class AttachToTangleTest extends AbstractApiTestCase
{
    protected function initValidRequest()
    {
        DummyData::init();
        $container = new Container();
        $this->request = new Request(
            $container->get(PowInterface::class),
            $this->httpClient,
            $container->get(CurlFactory::class),
            new Node()
        );
        $this->request->setTrunkTransactionHash(DummyData::getTransactionHash(0));
        $this->request->setBranchTransactionHash(DummyData::getTransactionHash(1));
        $this->request->setMinWeightMagnitude(18);
        $this->request->setTransactions([
            DummyData::getTransaction(0), DummyData::getTransaction(1)
        ]);
    }

    public function testRequestSerialization()
    {
        $serialized =$this->request->jsonSerialize();
        static::assertArrayHasKey('command', $serialized);
        static::assertEquals('attachToTangle', $serialized['command']);
        static::assertArrayHasKey('trunkTransaction', $serialized);
        static::assertArrayHasKey('branchTransaction', $serialized);
        static::assertArrayHasKey('minWeightMagnitude', $serialized);
        static::assertArrayHasKey('transactions', $serialized);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__ . '/fixtures/AttachToTangle.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertCount(2, $response->getTransactions());
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[0]);
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[1]);
        static::assertEquals('TRYTEVALUEHERE', (string)$response->getTransactions()[0]);
        static::assertEquals('TRYTEVALUEHEREBBB', (string)$response->getTransactions()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__ . '/fixtures/AddNeighbors.json', 'trytes']
        ];
    }

}