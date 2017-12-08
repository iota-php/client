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

namespace Techworker\IOTA\RemoteApi\Commands\FindTransactions;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Approvee;
use Techworker\IOTA\Type\BundleHash;
use Techworker\IOTA\Type\Tag;

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
    private $findTransactionsFactory;

    /**
     * Sets the factory for the request.
     * @param RequestFactory $findTransactionsFactory
     *
     * @return RequestTrait
     */
    protected function setFindTransactionsFactory(RequestFactory $findTransactionsFactory): self
    {
        $this->findTransactionsFactory = $findTransactionsFactory;

        return $this;
    }

    /* @noinspection MoreThanThreeArgumentsInspection */
    /**
     * Executes the request.
     * @param Node         $node
     * @param Address[]    $addresses
     * @param BundleHash[] $bundleHashes
     * @param Tag[]        $tags
     * @param Approvee[]   $approvees
     *
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function findTransactions(
        Node $node,
                                 array $addresses = [],
                                 array $bundleHashes = [],
                                 array $tags = [],
                                 array $approvees = []
    ): Response {
        $request = $this->findTransactionsFactory->factory($node);
        $request->setAddresses($addresses);
        $request->setBundleHashes($bundleHashes);
        $request->setTags($tags);
        $request->setApprovees($approvees);

        return $request->execute()->throwOnError();
    }
}
