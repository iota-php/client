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

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Cryptography\POW\PowInterface;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle\Request;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle\Response;
use Techworker\IOTA\Tests\Container;
use Techworker\IOTA\Tests\DummyData;
use Techworker\IOTA\Type\Trytes;

/**
 * @coversNothing
 */
class AttachToTangleTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $serialized = $this->request->jsonSerialize();
        static::assertArrayHasKey('command', $serialized);
        static::assertEquals('attachToTangle', $serialized['command']);
        static::assertArrayHasKey('trunkTransaction', $serialized);
        static::assertArrayHasKey('branchTransaction', $serialized);
        static::assertArrayHasKey('minWeightMagnitude', $serialized);
        static::assertArrayHasKey('transactions', $serialized);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/AttachToTangle.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->request->execute();

        static::assertCount(2, $response->getTransactions());
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[0]);
        static::assertInstanceOf(Trytes::class, $response->getTransactions()[1]);
        static::assertEquals('TRYTEVALUEHERE', (string) $response->getTransactions()[0]);
        static::assertEquals('TRYTEVALUEHEREBBB', (string) $response->getTransactions()[1]);
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/AddNeighbors.json', 'trytes'],
        ];
    }

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
            DummyData::getTransaction(0), DummyData::getTransaction(1),
        ]);
    }
}
