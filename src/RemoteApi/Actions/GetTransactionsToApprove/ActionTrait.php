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

namespace IOTA\RemoteApi\Actions\GetTransactionsToApprove;

use IOTA\Node;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\Type\Milestone;

/**
 * Trait RequestTrait.
 *
 * Wrapper function to execute the request.
 */
trait ActionTrait
{
    /**
     * The request factory.
     *
     * @var ActionFactory
     */
    private $getTransactionsToApproveFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $getTransactionsToApproveFactory
     *
     * @return ActionTrait
     */
    public function setGetTransactionsToApproveFactory(ActionFactory $getTransactionsToApproveFactory): self
    {
        $this->getTransactionsToApproveFactory = $getTransactionsToApproveFactory;

        return $this;
    }

    // @noinspection MoreThanThreeArgumentsInspection

    /**
     * Executes the request.
     *
     * @param Node           $node
     * @param int            $depth
     * @param null|int       $numWalks
     * @param null|Milestone $reference
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    protected function getTransactionsToApprove(
        Node $node,
                                                int $depth,
                                                int $numWalks = null,
                                                Milestone $reference = null
    ): Result {
        $request = $this->getTransactionsToApproveFactory->factory($node);
        $request->setDepth($depth);
        if (null !== $numWalks) {
            $request->setNumWalks($numWalks);
        }

        if (null !== $reference) {
            $request->setReference($reference);
        }

        return $request->execute();
    }
}
