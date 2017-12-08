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

namespace Techworker\IOTA\RemoteApi\Commands\AddNeighbors;

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
    private $addNeighborsFactory;

    /**
     * Sets the factory for the request.
     *
     * @param RequestFactory $addNeighborsFactory
     *
     * @return RequestTrait
     */
    protected function setAddNeighborsFactory(RequestFactory $addNeighborsFactory): self
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
     * @return AbstractResponse|Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \InvalidArgumentException
     * @throws Exception
     */
    protected function addNeighbors(Node $node, array $neighborUris): Response
    {
        $request = $this->addNeighborsFactory->factory($node);
        $request->setNeighborUris($neighborUris);

        return $request->execute()->throwOnError();
    }
}
