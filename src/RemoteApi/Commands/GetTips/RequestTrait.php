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

namespace Techworker\IOTA\RemoteApi\Commands\GetTips;

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
    private $getTipsFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $getTipsFactory
     *
     * @return RequestTrait
     */
    public function setGetTipsFactory(RequestFactory $getTipsFactory): self
    {
        $this->getTipsFactory = $getTipsFactory;

        return $this;
    }

    /**
     * Executes the request.
     * @param Node $node
     *
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getTips(Node $node): Response
    {
        $request = $this->getTipsFactory->factory($node);

        return $request->execute()->throwOnError();
    }
}
