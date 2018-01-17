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

namespace Techworker\IOTA\RemoteApi\Commands\GetNeighbors;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;

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
    private $getNeighborsFactory;

    /**
     * Sets the factory for the request.
     *
     * @param RequestFactory $getNeighborsFactory
     *
     * @return RequestTrait
     */
    protected function setGetNeighborsFactory(RequestFactory $getNeighborsFactory): self
    {
        $this->getNeighborsFactory = $getNeighborsFactory;

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
     * @return AbstractResponse|Response
     */
    protected function getNeighbors(Node $node): Response
    {
        $request = $this->getNeighborsFactory->factory($node);

        return $request->execute()->throwOnError();
    }
}
