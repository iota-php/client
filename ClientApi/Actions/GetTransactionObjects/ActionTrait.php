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

namespace Techworker\IOTA\ClientApi\Actions\GetTransactionObjects;

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\TransactionHash;

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
    private $getTransactionObjectsFactory;

    /**
     * @param ActionFactory $getTransactionObjectsFactory
     *
     * @return ActionTrait
     */
    protected function setGetTransactionObjectsFactory(ActionFactory $getTransactionObjectsFactory): self
    {
        $this->getTransactionObjectsFactory = $getTransactionObjectsFactory;

        return $this;
    }

    /**
     * @param Node              $node
     * @param TransactionHash[] $transactionHashes
     *
     * @return Result
     */
    protected function getTransactionObjects(
        Node $node,
                                 array $transactionHashes
    ): Result {
        $action = $this->getTransactionObjectsFactory->factory($node);
        $action->setTransactionHashes($transactionHashes);

        return $action->execute();
    }
}
