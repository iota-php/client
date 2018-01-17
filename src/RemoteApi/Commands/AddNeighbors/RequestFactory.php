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

namespace Techworker\IOTA\RemoteApi\Commands\AddNeighbors;

use Techworker\IOTA\AbstractFactory;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
use Techworker\IOTA\RemoteApi\RequestFactoryInterface;

/**
 * Class RequestFactory.
 *
 * Gets a new request instance.
 */
class RequestFactory extends AbstractFactory implements RequestFactoryInterface
{
    /**
     * Gets the new Request instance.
     *
     * @param Node $node
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return Request
     */
    public function factory(Node $node): Request
    {
        return new Request(
            $this->container->get(HttpClientInterface::class),
            $node
        );
    }
}
