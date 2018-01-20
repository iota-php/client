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

namespace Techworker\IOTA\RemoteApi\Actions\AttachToTangle;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Transaction;
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
    private $attachToTangleFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $attachToTangleFactory
     *
     * @return ActionTrait
     */
    protected function setAttachToTangleFactory(ActionFactory $attachToTangleFactory): self
    {
        $this->attachToTangleFactory = $attachToTangleFactory;

        return $this;
    }

    // @noinspection MoreThanThreeArgumentsInspection

    /**
     * Executes the request.
     *
     * @param Node            $node
     * @param Transaction[]   $transactions
     * @param TransactionHash $trunkTransactionHash
     * @param TransactionHash $branchTransactionHash
     * @param int             $minWeightMagnitude
     *
     * @throws \Techworker\IOTA\Exception
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     *
     * @return AbstractResult|Result
     */
    protected function attachToTangle(
        Node $node,
                                 array $transactions,
                                 TransactionHash $trunkTransactionHash,
                                 TransactionHash $branchTransactionHash,
                                 int $minWeightMagnitude
    ): Result {
        $request = $this->attachToTangleFactory->factory($node);
        $request->setTransactions($transactions);
        $request->setTrunkTransactionHash($trunkTransactionHash);
        $request->setBranchTransactionHash($branchTransactionHash);
        $request->setMinWeightMagnitude($minWeightMagnitude);

        return $request->execute()->throwOnError();
    }
}
