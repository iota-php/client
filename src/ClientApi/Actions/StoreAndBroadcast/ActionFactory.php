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

namespace Techworker\IOTA\ClientApi\Actions\StoreAndBroadcast;

use Techworker\IOTA\AbstractFactory;
use Techworker\IOTA\ClientApi\FactoryInterface;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions;
use Techworker\IOTA\RemoteApi\Commands\StoreTransactions;

/**
 * Class ActionFactory.
 *
 * Creates a new Action instance.
 */
class ActionFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * Creates a new action instance.
     *
     * @param Node $node
     *
     * @return Action
     */
    public function factory(Node $node): Action
    {
        return new Action(
            $node,
            $this->container->get(StoreTransactions\RequestFactory::class),
            $this->container->get(BroadcastTransactions\RequestFactory::class)
        );
    }
}
