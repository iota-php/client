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

namespace IOTA\ClientApi\Actions\FindTransactionObjects;

use IOTA\Node;
use IOTA\Type\Address;
use IOTA\Type\Approvee;
use IOTA\Type\BundleHash;
use IOTA\Type\Tag;

/**
 * Replays a transfer by doing Proof of Work again.
 */
trait ActionTrait
{
    /**
     * The action factory.
     *
     * @var ActionFactory
     */
    private $findTransactionObjectsFactory;

    /**
     * @param ActionFactory $findTransactionObjectsFactory
     *
     * @return ActionTrait
     */
    protected function setFindTransactionObjectsFactory(ActionFactory $findTransactionObjectsFactory): self
    {
        $this->findTransactionObjectsFactory = $findTransactionObjectsFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * Executes the action.
     *
     * @param Node         $node
     * @param Address[]    $addresses
     * @param BundleHash[] $bundleHashes
     * @param Tag[]        $tags
     * @param Approvee[]   $approvees
     *
     * @return Result
     */
    protected function findTransactionObjects(
        Node $node,
                                       array $addresses = [],
                                       array $bundleHashes = [],
                                       array $tags = [],
                                       array $approvees = []
    ): Result {
        $action = $this->findTransactionObjectsFactory->factory($node);
        $action->setAddresses($addresses);
        $action->setBundleHashes($bundleHashes);
        $action->setTags($tags);
        $action->setApprovees($approvees);

        return $action->execute();
    }
}
