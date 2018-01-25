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

namespace IOTA\ClientApi\Actions\FindTransactionObjects;

use IOTA\AbstractFactory;
use IOTA\ClientApi\Actions\GetTransactionObjects;
use IOTA\ClientApi\FactoryInterface;
use IOTA\Node;
use IOTA\RemoteApi\Actions\FindTransactions;

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
            $this->container->get(FindTransactions\ActionFactory::class),
            $this->container->get(GetTransactionObjects\ActionFactory::class)
        );
    }
}
