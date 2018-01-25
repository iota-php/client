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
use IOTA\Tests\ClientApi\Actions\AbstractActionTest;
use IOTA\Tests\DummyData;

/**
 * @coversNothing
 */
class FactoryTest extends AbstractActionTest
{
    public function testFactory()
    {
        $this->markTestSkipped('TODO');
        $factory = new BroadcastBundle\ActionFactory($this->container);
        static::assertInstanceOf(BroadcastBundle\Action::class, $factory->factory(DummyData::getNode()));
    }
}
