<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\ClientApi\Actions\FindTransactionObjects;

use Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use Techworker\IOTA\Tests\DummyData;

class FactoryTest extends AbstractActionTest
{
    public function testFactory()
    {
        $factory = new FindTransactionObjects\ActionFactory($this->container);
        static::assertInstanceOf(FindTransactionObjects\Action::class, $factory->factory(DummyData::getNode()));
    }
}