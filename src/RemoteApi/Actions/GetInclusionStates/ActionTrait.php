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

namespace Techworker\IOTA\RemoteApi\Actions\GetInclusionStates;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Type\TransactionHash;

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
    private $getInclusionStatesFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $getInclusionStatesFactory
     *
     * @return ActionTrait
     */
    protected function setGetInclusionStatesFactory(ActionFactory $getInclusionStatesFactory): self
    {
        $this->getInclusionStatesFactory = $getInclusionStatesFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node              $node
     * @param TransactionHash[] $transactionHashes
     * @param Tip[]             $tips
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResult|Result
     */
    protected function getInclusionStates(
        Node $node,
                                 array $transactionHashes,
                                array $tips
    ): Result {
        $request = $this->getInclusionStatesFactory->factory($node);
        $request->setTransactionHashes($transactionHashes);
        $request->setTips($tips);

        return $request->execute()->throwOnError();
    }
}
