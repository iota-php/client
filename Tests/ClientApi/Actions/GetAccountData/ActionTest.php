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

namespace Techworker\IOTA\Tests\ClientApi\Actions\GetAccountData;

use Techworker\IOTA\ClientApi\Actions\GetAccountData;
use Techworker\IOTA\ClientApi\Actions\GetNewAddress;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use Techworker\IOTA\Tests\DummyData;
use Techworker\IOTA\Type\SecurityLevel;

/**
 * @coversNothing
 */
class ActionTest extends AbstractActionTest
{
    public function testSetter()
    {
        $this->markTestSkipped('TODO');
        $action = new GetAccountData\Action(
            DummyData::getNode(),
            $this->caMocks->getNewAddressFactory(),
            $this->caMocks->getBundlesFromAddressesFactory(),
            $this->raMocks->getBalancesFactory()
        );

        // test add*
        $action->setSeed(DummyData::getSeed());
        $action->setSecurity(SecurityLevel::LEVEL_3());
        $action->setStartIndex(1);

        static::assertEquals(
            DummyData::getSeed(),
            static::readAttribute($action, 'seed')
        );
        static::assertEquals(
            3,
            static::readAttribute($action, 'security')->getLevel()
        );
        static::assertEquals(
            1,
            static::readAttribute($action, 'startIndex')
        );
    }

    public function testJsonSerialize()
    {
        $this->markTestSkipped('TODO');
        $action = new GetAccountData\Action(
            DummyData::getNode(),
            $this->caMocks->getNewAddressFactory(),
            $this->caMocks->getBundlesFromAddressesFactory(),
            $this->raMocks->getBalancesFactory()
        );

        $action->setSeed(DummyData::getSeed());
        $action->setSecurity(SecurityLevel::LEVEL_1());
        $action->setStartIndex(3);

        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('seed', $serialized);
        static::assertArrayHasKey('security', $serialized);
        static::assertArrayHasKey('startIndex', $serialized);

        static::assertEquals((string) DummyData::getSeed(), $serialized['seed']);
        static::assertEquals(1, $serialized['security']);
        static::assertEquals(3, $serialized['startIndex']);
    }

    public function testExecute()
    {
        $this->markTestSkipped('TODO');
        $naResponse = new GetNewAddress\Result();
        $action = new GetAccountData\Action(
            DummyData::getNode(),
            $this->caMocks->getNewAddressFactory(),
            $this->caMocks->getBundlesFromAddressesFactory(),
            $this->raMocks->getBalancesFactory()
        );

        /*
        $ftResponse = $this->raMocks->findTransactionsResponse([
            DummyData::getTransactionHash(0),
            DummyData::getTransactionHash(1)
        ]);

        $ftRequest = $this->raMocks->findTransactionsRequest($ftResponse);
        $ftRequest->expects($this->once())->method('execute');

        $gtAction = $this->caMocks->getTransactionObjectsAction(null);
        $gtAction->expects($this->once())->method('execute');

        $action = new FindTransactionObjects\Action(
            DummyData::getNode(),
            $this->raMocks->findTransactionsFactory($ftRequest),
            $this->caMocks->getTransactionObjectsFactory($gtAction)
        );

        $action->execute();*/
    }
}
