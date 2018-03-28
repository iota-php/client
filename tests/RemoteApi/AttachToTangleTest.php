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

use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Mock\Client;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Cryptography\POW\PowInterface;
use IOTA\Node;
use IOTA\RemoteApi\Actions\AttachToTangle\Action;
use IOTA\RemoteApi\Actions\AttachToTangle\Result;
use IOTA\RemoteApi\NodeApiClient;
use IOTA\Tests\Container;
use IOTA\Tests\DummyData;
use IOTA\Type\Trytes;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class AttachToTangleTest extends TestCase
{
    public function testAction()
    {
        DummyData::init();
        $container = new Container();

        $messageFactory = new GuzzleMessageFactory();

        $jsonBody = \file_get_contents(__DIR__.'/fixtures/AttachToTangle.json');

        $response = $messageFactory->createResponse(
            200,
            null,
            ['Content-Type' => 'application/json'],
            $jsonBody
        );

        // new mock and node api client
        $client = new Client();
        $client->addResponse($response);
        $nodeApiClient = new NodeApiClient($client, $client, $messageFactory);

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
        $action->setTransactions(
            [
                DummyData::getTransaction(0),
                DummyData::getTransaction(1),
            ]
        );

        /** @var Result $response */
        $result = $action->execute();

        static::assertCount(1, $result->getTransactions());
        static::assertInstanceOf(Trytes::class, $result->getTransactions()[0]);
        static::assertEquals(\json_decode($jsonBody, true)['trytes'][0], (string)$result->getTransactions()[0]);

        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('command', $serialized);
        static::assertEquals('attachToTangle', $serialized['command']);
        static::assertArrayHasKey('trunkTransaction', $serialized);
        static::assertArrayHasKey('branchTransaction', $serialized);
        static::assertArrayHasKey('minWeightMagnitude', $serialized);
        static::assertArrayHasKey('trytes', $serialized);
    }
}
