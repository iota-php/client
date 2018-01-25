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

namespace Techworker\IOTA\RemoteApi\Actions\GetTrytes;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;
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
    private $getTrytesFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $getTrytesFactory
     *
     * @return ActionTrait
     */
    public function setGetTrytesFactory(ActionFactory $getTrytesFactory): self
    {
        $this->getTrytesFactory = $getTrytesFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node              $node
     * @param TransactionHash[] $transactionHashes
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResult|Result
     */
    protected function getTrytes(Node $node, array $transactionHashes): Result
    {
        $request = $this->getTrytesFactory->factory($node);
        $request->setTransactionHashes($transactionHashes);

        return $request->execute();
    }
}
