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

use Http\Discovery\MessageFactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Cryptography\POW\PowInterface;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Actions\AttachToTangle\Action;
use Techworker\IOTA\RemoteApi\Actions\AttachToTangle\Result;
use Techworker\IOTA\RemoteApi\NodeApiClient;
use Techworker\IOTA\Tests\Container;
use Techworker\IOTA\Tests\DummyData;
use Techworker\IOTA\Type\Trytes;

/**
 * @coversNothing
 */
class AttachToTangleTest extends TestCase
{
    public function testAction()
    {
        DummyData::init();
        $container = new Container();

        // get fixture
        $jsonBody = file_get_contents(__DIR__.'/fixtures/AttachToTangle.json');
        // create fake response
        $response = MessageFactoryDiscovery::find()->createResponse(200,
            null, ['Content-Type' => 'application/json'], $jsonBody
        );

        // new mock and node api client
        $client = new Client();
        $client->addResponse($response);
        $nodeApiClient = new NodeApiClient(
            $client, $client, MessageFactoryDiscovery::find()
        );

        // create new action
        $action = new Action(
            $container->get(PowInterface::class),
            $nodeApiClient,
            $container->get(CurlFactory::class),
            new Node('', true) // pow = true!
        );
        $action->setTrunkTransactionHash(DummyData::getTransactionHash(0));
        $action->setBranchTransactionHash(DummyData::getTransactionHash(1));
        $action->setMinWeightMagnitude(18);
        $action->setTransactions([
            DummyData::getTransaction(0), DummyData::getTransaction(1),
        ]);

        /** @var Result $response */
        $result = $action->execute();

        static::assertCount(1, $result->getTransactions());
        static::assertInstanceOf(Trytes::class, $result->getTransactions()[0]);
        static::assertEquals(json_decode($jsonBody, true)['trytes'][0], (string) $result->getTransactions()[0]);

        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('command', $serialized);
        static::assertEquals('attachToTangle', $serialized['command']);
        static::assertArrayHasKey('trunkTransaction', $serialized);
        static::assertArrayHasKey('branchTransaction', $serialized);
        static::assertArrayHasKey('minWeightMagnitude', $serialized);
        static::assertArrayHasKey('trytes', $serialized);
    }
}
