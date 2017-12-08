<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\ClientApi\Actions\FindTransactionObjects;

use Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;
use Techworker\IOTA\RemoteApi\Commands\FindTransactions;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use Techworker\IOTA\Tests\DummyData;

class ActionTest extends AbstractActionTest
{
    public function testSetter()
    {
        $action = new FindTransactionObjects\Action(
            DummyData::getNode(),
            $this->raMocks->findTransactionsFactory(),
            $this->caMocks->getTransactionObjectsFactory()
        );

        // test add*
        $action->addAddress(DummyData::getAddress());
        $action->addBundleHash(DummyData::getBundleHash());
        $action->addApprovee(DummyData::getApprovee());
        $action->addTag(DummyData::getTag());

        static::assertEquals(
            [DummyData::getAddress()],
            static::readAttribute($action, 'addresses')
        );
        static::assertEquals(
            [DummyData::getBundleHash()],
            static::readAttribute($action, 'bundleHashes')
        );
        static::assertEquals(
            [DummyData::getTag()],
            static::readAttribute($action, 'tags')
        );
        static::assertEquals(
            [DummyData::getApprovee()],
            static::readAttribute($action, 'approvees')
        );

        $action = new FindTransactionObjects\Action(
            DummyData::getNode(),
            $this->raMocks->findTransactionsFactory(),
            $this->caMocks->getTransactionObjectsFactory()
        );

        // test add*
        $action->setAddresses([DummyData::getAddress()]);
        $action->setBundleHashes([DummyData::getBundleHash()]);
        $action->setApprovees([DummyData::getApprovee()]);
        $action->setTags([DummyData::getTag()]);

        static::assertEquals(
            [DummyData::getAddress()],
            static::readAttribute($action, 'addresses')
        );
        static::assertEquals(
            [DummyData::getBundleHash()],
            static::readAttribute($action, 'bundleHashes')
        );
        static::assertEquals(
            [DummyData::getTag()],
            static::readAttribute($action, 'tags')
        );
        static::assertEquals(
            [DummyData::getApprovee()],
            static::readAttribute($action, 'approvees')
        );
    }

    public function testJsonSerialize()
    {
        $action = new FindTransactionObjects\Action(
            DummyData::getNode(),
            $this->raMocks->findTransactionsFactory(),
            $this->caMocks->getTransactionObjectsFactory()
        );

        $action->addAddress(DummyData::getAddress());
        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('addresses', $serialized);
        static::assertEquals([(string)DummyData::getAddress()], $serialized['addresses']);

        $action->addBundleHash(DummyData::getBundleHash());
        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('bundleHashes', $serialized);
        static::assertEquals([(string)DummyData::getBundleHash()], $serialized['bundleHashes']);

        $action->addApprovee(DummyData::getApprovee());
        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('approvees', $serialized);
        static::assertEquals([(string)DummyData::getApprovee()], $serialized['approvees']);

        $action->addTag(DummyData::getTag());
        $serialized = $action->jsonSerialize();
        static::assertArrayHasKey('tags', $serialized);
        static::assertEquals([(string)DummyData::getTag()], $serialized['tags']);

        $this->assertSerializedActionHasNode($serialized, DummyData::getNode());
    }

    public function testExecute()
    {
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

        $action->execute();
    }
}