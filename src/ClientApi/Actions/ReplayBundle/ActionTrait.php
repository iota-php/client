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

namespace IOTA\ClientApi\Actions\ReplayBundle;

use IOTA\Node;
use IOTA\Type\TransactionHash;

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
    private $replayBundleFactory;

    /**
     * @param ActionFactory $replayBundleFactory
     *
     * @return ActionTrait
     */
    protected function setReplayBundleFactory(ActionFactory $replayBundleFactory): self
    {
        $this->replayBundleFactory = $replayBundleFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node            $node
     * @param TransactionHash $tailTransactionHash
     * @param int             $depth
     * @param int             $minWeightMagnitude
     *
     * @return Result
     */
    protected function replayBundle(
        Node $node,
        TransactionHash $tailTransactionHash,
        int $depth,
        int $minWeightMagnitude
    ): Result {
        $action = $this->replayBundleFactory->factory($node);
        $action->setTailTransactionHash($tailTransactionHash);
        $action->setMinWeightMagnitude($minWeightMagnitude);
        $action->setDepth($depth);

        return $action->execute();
    }
}
