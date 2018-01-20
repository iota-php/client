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

namespace Techworker\IOTA\RemoteApi\Actions\IsTailConsistent;

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
    private $isTailConsistentFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $isTailConsistentFactory
     *
     * @return ActionTrait
     */
    public function setIsTailConsistentFactory(ActionFactory $isTailConsistentFactory): self
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
     * @return AbstractResult|Result
     */
    protected function isTailConsistent(Node $node, TransactionHash $tailTransactionHash): Result
    {
        $request = $this->isTailConsistentFactory->factory($node);
        $request->setTailTransactionHash($tailTransactionHash);

        return $request->execute()->throwOnError();
    }
}
