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

namespace Techworker\IOTA\RemoteApi\Commands\IsTailConsistent;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\TransactionHash;

/**
 * Trait RequestTrait.
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
    private $isTailConsistentFactory;

    /**
     * Sets the factory for the request.
     *
     * @param RequestFactory $isTailConsistentFactory
     *
     * @return RequestTrait
     */
    public function setIsTailConsistentFactory(RequestFactory $isTailConsistentFactory): self
    {
        $this->isTailConsistentFactory = $isTailConsistentFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node            $node
     * @param TransactionHash $tailTransactionHash
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResponse|Response
     */
    protected function isTailConsistent(Node $node, TransactionHash $tailTransactionHash): Response
    {
        $request = $this->isTailConsistentFactory->factory($node);
        $request->setTailTransactionHash($tailTransactionHash);

        return $request->execute()->throwOnError();
    }
}
