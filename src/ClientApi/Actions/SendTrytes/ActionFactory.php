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

namespace IOTA\ClientApi\Actions\SendTrytes;

use IOTA\AbstractFactory;
use IOTA\ClientApi\Actions\StoreAndBroadcast;
use IOTA\ClientApi\FactoryInterface;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Node;
use IOTA\RemoteApi\Actions\AttachToTangle;
use IOTA\RemoteApi\Actions\GetTransactionsToApprove;

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
            $this->container->get(GetTransactionsToApprove\ActionFactory::class),
            $this->container->get(AttachToTangle\ActionFactory::class),
            $this->container->get(StoreAndBroadcast\ActionFactory::class),
            $this->container->get(CurlFactory::class)
        );
    }
}
