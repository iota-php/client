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

namespace Techworker\IOTA\RemoteApi\Commands\GetBalances;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Address;

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
    private $getBalancesFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $getBalancesFactory
     *
     * @return RequestTrait
     */
    protected function setGetBalancesFactory(RequestFactory $getBalancesFactory): self
    {
        $this->getBalancesFactory = $getBalancesFactory;

        return $this;
    }

    /**
     * Executes the request.
     * @param Node      $node
     * @param Address[] $addresses
     * @param int       $threshold
     *
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getBalances(
        Node $node,
                                 array $addresses,
                                 int $threshold = 100
    ): Response {
        $request = $this->getBalancesFactory->factory($node);
        $request->setAddresses($addresses);
        $request->setThreshold($threshold);

        return $request->execute()->throwOnError();
    }
}
