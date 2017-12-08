<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Tests\ClientApi\Actions\BroadcastBundle;

use Techworker\IOTA\ClientApi\Actions\BroadcastBundle;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use Techworker\IOTA\Tests\DummyData;

class FactoryTest extends AbstractActionTest
{
    public function testFactory()
    {
        $factory = new BroadcastBundle\ActionFactory($this->container);
        static::assertInstanceOf(BroadcastBundle\Action::class, $factory->factory(DummyData::getNode()));
    }
}