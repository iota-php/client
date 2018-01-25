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

namespace IOTA\RemoteApi\Actions\GetNodeInfo;

use IOTA\Node;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;

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
    private $getNodeInfoFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $getNodeInfoFactory
     *
     * @return ActionTrait
     */
    protected function setGetNodeInfoFactory(ActionFactory $getNodeInfoFactory): self
    {
        $this->getNodeInfoFactory = $getNodeInfoFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node $node
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResult|Result
     */
    protected function getNodeInfo(Node $node): Result
    {
        $request = $this->getNodeInfoFactory->factory($node);

        return $request->execute();
    }
}
