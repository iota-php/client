<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\RemoteApi\Commands\AttachToTangle;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\TransactionHash;

/**
 * Trait RequestTrait
 *
 * Wrapper function to execute the request.
 */
trait RequestTrait
{
    /**
     * The request factory.
     *
     * @var RequestFactory
     */
    private $attachToTangleFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $attachToTangleFactory
     *
     * @return RequestTrait
     */
    protected function setAttachToTangleFactory(RequestFactory $attachToTangleFactory): self
    {
        $this->attachToTangleFactory = $attachToTangleFactory;

        return $this;
    }

    /* @noinspection MoreThanThreeArgumentsInspection */
    /**
     * Executes the request.
     * @param Node            $node
     * @param Transaction[]   $transactions
     * @param TransactionHash $trunkTransactionHash
     * @param TransactionHash $branchTransactionHash
     * @param int             $minWeightMagnitude
     *
     * @return AbstractResponse|Response
     * @throws \Techworker\IOTA\Exception
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     */
    protected function attachToTangle(
        Node $node,
                                 array $transactions,
                                 TransactionHash $trunkTransactionHash,
                                 TransactionHash $branchTransactionHash,
                                 int $minWeightMagnitude
    ): Response {
        $request = $this->attachToTangleFactory->factory($node);
        $request->setTransactions($transactions);
        $request->setTrunkTransactionHash($trunkTransactionHash);
        $request->setBranchTransactionHash($branchTransactionHash);
        $request->setMinWeightMagnitude($minWeightMagnitude);

        return $request->execute()->throwOnError();
    }
}
