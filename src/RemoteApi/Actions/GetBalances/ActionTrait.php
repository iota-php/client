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

namespace Techworker\IOTA\RemoteApi\Actions\GetBalances;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Address;

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
    private $getBalancesFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $getBalancesFactory
     *
     * @return ActionTrait
     */
    protected function setGetBalancesFactory(ActionFactory $getBalancesFactory): self
    {
        $this->getBalancesFactory = $getBalancesFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node      $node
     * @param Address[] $addresses
     * @param int       $threshold
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResult|Result
     */
    protected function getBalances(
        Node $node,
                                 array $addresses,
                                 int $threshold = 100
    ): Result {
        $request = $this->getBalancesFactory->factory($node);
        $request->setAddresses($addresses);
        $request->setThreshold($threshold);

        return $request->execute();
    }
}
