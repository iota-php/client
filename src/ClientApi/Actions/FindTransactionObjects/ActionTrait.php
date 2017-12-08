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

namespace Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Approvee;
use Techworker\IOTA\Type\BundleHash;
use Techworker\IOTA\Type\Tag;

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
