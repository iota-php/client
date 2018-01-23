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

namespace Techworker\IOTA\RemoteApi\Actions\StoreTransactions;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Transaction;

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
    private $storeTransactionsFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $storeTransactionsFactory
     *
     * @return ActionTrait
     */
    protected function setStoreTransactionsFactory(ActionFactory $storeTransactionsFactory): self
    {
        $this->storeTransactionsFactory = $storeTransactionsFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node          $node
     * @param Transaction[] $transactions
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResult|Result
     */
    protected function storeTransactions(Node $node, array $transactions): Result
    {
        $request = $this->storeTransactionsFactory->factory($node);
        $request->setTransactions($transactions);

        return $request->execute();
    }
}
