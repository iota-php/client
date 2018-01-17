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

namespace Techworker\IOTA\ClientApi\Actions\SendTrytes;

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Milestone;

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
    private $sendTrytesFactory;

    /**
     * @param ActionFactory $sendTrytesFactory
     *
     * @return ActionTrait
     */
    protected function setSendTrytesFactory(ActionFactory $sendTrytesFactory): self
    {
        $this->sendTrytesFactory = $sendTrytesFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node           $node
     * @param array          $transactions
     * @param int            $minWeightMagnitude
     * @param int            $depth
     * @param null|Milestone $reference
     *
     * @return Result
     */
    protected function sendTrytes(
        Node $node,
                                    array $transactions,
                                    int $minWeightMagnitude,
                                    int $depth,
                                    Milestone $reference = null
    ): Result {
        $action = $this->sendTrytesFactory->factory($node);
        $action->setTransactions($transactions);
        $action->setMinWeightMagnitude($minWeightMagnitude);
        $action->setDepth($depth);

        if (null !== $reference) {
            $action->setReference($reference);
        }

        return $action->execute();
    }
}
