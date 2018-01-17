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

namespace Techworker\IOTA\ClientApi\Actions\PromoteTransaction;

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Milestone;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Type\Transfer;

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
    private $promoteTransactionFactory;

    /**
     * @param ActionFactory $promoteTransactionFactory
     *
     * @return ActionTrait
     */
    protected function setPromoteTransactionFactory(ActionFactory $promoteTransactionFactory): self
    {
        $this->promoteTransactionFactory = $promoteTransactionFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node            $node
     * @param TransactionHash $tailTransactionHash
     * @param int             $depth
     * @param int             $minWeightMagnitude
     * @param Transfer        $transfer
     * @param Milestone       $reference
     *
     * @return Result
     */
    protected function promoteTransaction(
        Node $node,
        TransactionHash $tailTransactionHash,
        int $depth,
        int $minWeightMagnitude,
        Transfer $transfer,
        Milestone $reference
    ): Result {
        $action = $this->promoteTransactionFactory->factory($node);
        $action->setTailTransactionHash($tailTransactionHash);
        $action->setMinWeightMagnitude($minWeightMagnitude);
        $action->setDepth($depth);
        $action->setTransfer($transfer);
        $action->setReference($reference);

        return $action->execute();
    }
}
