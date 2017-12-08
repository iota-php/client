<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\ClientApi\Actions\BroadcastBundle;

use Techworker\IOTA\ClientApi\Actions\BroadcastBundle;
use Techworker\IOTA\ClientApi\VoidResult;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use Techworker\IOTA\Tests\DummyData;

class ActionTest extends AbstractActionTest
{
    public function testSetter()
    {
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
        $action = new BroadcastBundle\Action(
            DummyData::getNode(),
            $this->caMocks->getBundleFactory(),
            $this->raMocks->broadcastTransactionsFactory()
        );
        $action->setTailTransactionHash(DummyData::getTransactionHash());

        $serialized = $action->jsonSerialize();
        $action->setTailTransactionHash(DummyData::getTransactionHash());
        static::assertEquals((string)DummyData::getTransactionHash(), $serialized['tailTransactionHash']);
        $this->assertSerializedActionHasNode($serialized, DummyData::getNode());
    }

    public function testExecute()
    {
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