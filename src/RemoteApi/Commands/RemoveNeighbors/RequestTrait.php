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

namespace Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;

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
    protected $removeNeighborsFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $removeNeighborsFactory
     *
     * @return RequestTrait
     */
    protected function setRemoveNeighborsFactory(RequestFactory $removeNeighborsFactory): self
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
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     */
    protected function removeNeighbors(Node $node, array $neighborUris): Response
    {
        $request = $this->removeNeighborsFactory->factory($node);
        $request->setNeighborUris($neighborUris);

        return $request->execute()->throwOnError();
    }
}
