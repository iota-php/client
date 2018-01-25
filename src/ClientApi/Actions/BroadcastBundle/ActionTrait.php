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

namespace IOTA\ClientApi\Actions\BroadcastBundle;

use IOTA\ClientApi\VoidResult;
use IOTA\Node;
use IOTA\Type\TransactionHash;

/**
 * Takes a tail transaction hash as input, gets the bundle associated with the
 * transaction and then rebroadcasts the entire bundle.
 */
trait ActionTrait
{
    /**
     * The action factory.
     *
     * @var ActionFactory
     */
    private $broadcastBundleFactory;

    /**
     * @param ActionFactory $broadcastBundleFactory
     *
     * @return ActionTrait
     */
    protected function setBroadcastBundleFactory(ActionFactory $broadcastBundleFactory): self
    {
        $this->broadcastBundleFactory = $broadcastBundleFactory;

        return $this;
    }

    /**
     * Executes the action.
     *
     * @param Node            $node
     * @param TransactionHash $transactionHash
     *
     * @return VoidResult
     */
    protected function broadcastBundle(
        Node $node,
                                    TransactionHash $transactionHash
    ): VoidResult {
        $action = $this->broadcastBundleFactory->factory($node);
        $action->setTailTransactionHash($transactionHash);

        return $action->execute();
    }
}
