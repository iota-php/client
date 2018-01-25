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

namespace IOTA\Tests\ClientApi\Actions\BroadcastBundle;

use IOTA\ClientApi\Actions\BroadcastBundle;
use IOTA\ClientApi\VoidResult;
use IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use IOTA\Tests\DummyData;

/**
 * @coversNothing
 */
class ActionTest extends AbstractActionTest
{
    public function testSetter()
    {

        $this->markTestSkipped('TODO');
        $action = new BroadcastBundle\Action(
            DummyData::getNode(),
            $this->caMocks->getBundleFactory(),
            $this->raMocks->broadcastTransactionsFactory()
        );
        $action->setTailTransactionHash(DummyData::getTransactionHash());

        static::assertEquals(
            DummyData::getTransactionHash(),
            static::readAttribute($action, 'tailTransactionHash')
        );
    }

    public function testJsonSerialize()
    {
        $this->markTestSkipped('TODO');
        $action = new BroadcastBundle\Action(
            DummyData::getNode(),
            $this->caMocks->getBundleFactory(),
            $this->raMocks->broadcastTransactionsFactory()
        );
        $action->setTailTransactionHash(DummyData::getTransactionHash());

        $serialized = $action->jsonSerialize();
        $action->setTailTransactionHash(DummyData::getTransactionHash());
        static::assertEquals((string) DummyData::getTransactionHash(), $serialized['tailTransactionHash']);
        $this->assertSerializedActionHasNode($serialized, DummyData::getNode());
    }

    public function testExecute()
    {
        $this->markTestSkipped('TODO');
        $btRequest = $this->raMocks->broadcastTransactionsRequest();
        $btRequest->expects($this->once())->method('execute');

        $gbAction = $this->caMocks->getBundleAction();
        $gbAction->expects($this->once())->method('execute');

        /** @var BroadcastBundle\Action|\PHPUnit_Framework_MockObject_MockObject $action */
        $action = new BroadcastBundle\Action(
            DummyData::getNode(),
            $this->caMocks->getBundleFactory($gbAction),
            $this->raMocks->broadcastTransactionsFactory($btRequest)
        );
        $action->setTailTransactionHash(DummyData::getTransactionHash());
        $result = $action->execute();
        static::assertInstanceOf(VoidResult::class, $result);
    }
}
