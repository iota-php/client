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

namespace Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Milestone;

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
    private $getTransactionsToApproveFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $getTransactionsToApproveFactory
     *
     * @return RequestTrait
     */
    public function setGetTransactionsToApproveFactory(RequestFactory $getTransactionsToApproveFactory): self
    {
        $this->getTransactionsToApproveFactory = $getTransactionsToApproveFactory;

        return $this;
    }

    /* @noinspection MoreThanThreeArgumentsInspection */
    /**
     * Executes the request.
     *
     * @param Node $node
     * @param int $depth
     * @param int|null $numWalks
     * @param Milestone|null $reference
     * @return Response|AbstractResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws Exception
     */
    protected function getTransactionsToApprove(
        Node $node,
                                                int $depth,
                                                int $numWalks = null,
                                                Milestone $reference = null
    ): Response {
        $request = $this->getTransactionsToApproveFactory->factory($node);
        $request->setDepth($depth);
        if (null !== $numWalks) {
            $request->setNumWalks($numWalks);
        }

        if (null !== $reference) {
            $request->setReference($reference);
        }

        return $request->execute()->throwOnError();
    }
}
