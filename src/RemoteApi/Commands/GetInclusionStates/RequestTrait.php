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

namespace Techworker\IOTA\RemoteApi\Commands\GetInclusionStates;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Type\TransactionHash;

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
    private $getInclusionStatesFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $getInclusionStatesFactory
     *
     * @return RequestTrait
     */
    protected function setGetInclusionStatesFactory(RequestFactory $getInclusionStatesFactory): self
    {
        $this->getInclusionStatesFactory = $getInclusionStatesFactory;

        return $this;
    }

    /**
     * Executes the request.
     * @param Node              $node
     * @param TransactionHash[] $transactionHashes
     * @param Tip[]             $tips
     *
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getInclusionStates(
        Node $node,
                                 array $transactionHashes,
                                array $tips
    ): Response {
        $request = $this->getInclusionStatesFactory->factory($node);
        $request->setTransactionHashes($transactionHashes);
        $request->setTips($tips);

        return $request->execute()->throwOnError();
    }
}
