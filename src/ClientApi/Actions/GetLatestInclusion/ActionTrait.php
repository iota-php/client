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

namespace IOTA\ClientApi\Actions\GetLatestInclusion;

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
    private $getLatestInclusionFactory;

    /**
     * @param ActionFactory $getLatestInclusionFactory
     *
     * @return ActionTrait
     */
    protected function setGetLatestInclusionFactory(ActionFactory $getLatestInclusionFactory): self
    {
        $this->getLatestInclusionFactory = $getLatestInclusionFactory;

        return $this;
    }

    /**
     * @param Node              $node
     * @param TransactionHash[] $transactionHashes
     *
     * @return Result
     */
    protected function getLatestInclusion(
        Node $node,
                                 array $transactionHashes
    ): Result {
        $action = $this->getLatestInclusionFactory->factory($node);
        $action->setTransactionHashes($transactionHashes);

        return $action->execute();
    }
}
