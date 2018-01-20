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

namespace Techworker\IOTA\RemoteApi\Actions\RemoveNeighbors;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;

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
    protected $removeNeighborsFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $removeNeighborsFactory
     *
     * @return ActionTrait
     */
    protected function setRemoveNeighborsFactory(ActionFactory $removeNeighborsFactory): self
    {
        $this->removeNeighborsFactory = $removeNeighborsFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node  $node
     * @param array $neighborUris
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     *
     * @return AbstractResult|Result
     */
    protected function removeNeighbors(Node $node, array $neighborUris): Result
    {
        $request = $this->removeNeighborsFactory->factory($node);
        $request->setNeighborUris($neighborUris);

        return $request->execute()->throwOnError();
    }
}
