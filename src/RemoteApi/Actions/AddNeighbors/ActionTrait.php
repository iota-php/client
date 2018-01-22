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

namespace Techworker\IOTA\RemoteApi\Actions\AddNeighbors;

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
    private $addNeighborsFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $addNeighborsFactory
     *
     * @return ActionTrait
     */
    protected function setAddNeighborsFactory(ActionFactory $addNeighborsFactory): self
    {
        $this->addNeighborsFactory = $addNeighborsFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node  $node
     * @param array $neighborUris
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \InvalidArgumentException
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    protected function addNeighbors(Node $node, array $neighborUris): Result
    {
        $request = $this->addNeighborsFactory->factory($node);
        $request->setNeighborUris($neighborUris);

        return $request->execute();
    }
}
