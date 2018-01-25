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

use Techworker\IOTA\ClientApi\VoidResult;
use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Transaction;

/**
 * Replays a transfer by doing Proof of Work again.
 */
trait ActionTrait
{
    /**
     * The action factory.
     *
     * @var ActionFactory
     */
    private $storeAndBroadcastFactory;

    /**
     * @param ActionFactory $storeAndBroadcastFactory
     *
     * @return ActionTrait
     */
    protected function setStoreAndBroadcastFactory(ActionFactory $storeAndBroadcastFactory): self
    {
        $this->storeAndBroadcastFactory = $storeAndBroadcastFactory;

        return $this;
    }

    /**
     * @param Node          $node
     * @param Transaction[] $transactions
     *
     * @return VoidResult
     */
    protected function storeAndBroadcast(
        Node $node,
                                 array $transactions
    ): VoidResult {
        $action = $this->storeAndBroadcastFactory->factory($node);
        $action->setTransactions($transactions);

        return $action->execute();
    }
}
