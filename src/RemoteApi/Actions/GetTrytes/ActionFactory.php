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

namespace IOTA\RemoteApi\Actions\GetTrytes;

use IOTA\AbstractFactory;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Node;
use IOTA\RemoteApi\ActionFactoryInterface;
use IOTA\RemoteApi\NodeApiClient;

/**
 * Class RequestFactory.
 *
 * Gets a new request instance.
 */
class ActionFactory extends AbstractFactory implements ActionFactoryInterface
{
    /**
     * Creates the new request instance and returns it.
     *
     * @param Node $node
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return Action
     */
    public function factory(Node $node): Action
    {
        return new Action(
            $this->container->get(NodeApiClient::class),
            $this->container->get(CurlFactory::class),
            $node
        );
    }
}
