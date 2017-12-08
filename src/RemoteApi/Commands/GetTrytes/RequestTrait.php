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

namespace Techworker\IOTA\RemoteApi\Commands\GetTrytes;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
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
    private $getTrytesFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $getTrytesFactory
     *
     * @return RequestTrait
     */
    public function setGetTrytesFactory(RequestFactory $getTrytesFactory): self
    {
        $this->getTrytesFactory = $getTrytesFactory;

        return $this;
    }

    /**
     * Executes the request.
     * @param Node              $node
     * @param TransactionHash[] $transactionHashes
     *
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getTrytes(Node $node, array $transactionHashes): Response
    {
        $request = $this->getTrytesFactory->factory($node);
        $request->setTransactionHashes($transactionHashes);

        return $request->execute()->throwOnError();
    }
}
