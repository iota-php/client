<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\ClientApi\Actions\FindTransactionObjects;

use Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use Techworker\IOTA\Tests\DummyData;

class ResultTest extends AbstractActionTest
{
    public function testSetter()
    {
        $t1 = DummyData::getTransaction(0)->parse();
        $t2 = DummyData::getTransaction(1)->parse();
        $result = new FindTransactionObjects\Result();
        $result->addTransaction($t1);
        $result->addTransaction($t2);

        static::assertCount(2, $result->getTransactions());
        static::assertEquals($t1, $result->getTransactions()[0]);
        static::assertEquals($t1, $result->getTransactions()[1]);
    }

    public function testSerialize()
    {
        $t1 = DummyData::getTransaction(0)->parse();
        $t2 = DummyData::getTransaction(1)->parse();
        $result = new FindTransactionObjects\Result();
        $result->addTransaction($t1);
        $result->addTransaction($t1);

        $serialized = $result->jsonSerialize();
        static::assertArrayHasKey('transactions', $serialized);
        static::assertEquals($t1->jsonSerialize(), $serialized['transactions'][0]);
        static::assertEquals($t2->jsonSerialize(), $serialized['transactions'][1]);
    }
}