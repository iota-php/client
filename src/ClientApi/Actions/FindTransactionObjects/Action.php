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

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\ClientApi\Actions\GetTransactionObjects;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\FindTransactions;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Approvee;
use Techworker\IOTA\Type\BundleHash;
use Techworker\IOTA\Type\Tag;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * This action will search for the given addresses/bundles/tags/approvees
 * and returns a list of parsed transaction objects.
 */
class Action extends AbstractAction
{
    use FindTransactions\RequestTrait,
        GetTransactionObjects\ActionTrait;

    /**
     * A list of bundle hashes to search for.
     *
     * @var BundleHash[]
     */
    protected $bundleHashes = [];

    /**
     * A list of addresses to search for.
     *
     * @var Address[]
     */
    protected $addresses = [];

    /**
     * A list of tags to search for.
     *
     * @var Tag[]
     */
    protected $tags = [];

    /**
     * A list of approvees to search for.
     *
     * @var Approvee[]
     */
    protected $approvees = [];

    /**
     * Action constructor.
     *
     * @param Node                                $node
     * @param FindTransactions\RequestFactory     $findTransactionsFactory
     * @param GetTransactionObjects\ActionFactory $getTransactionObjectsFactory
     */
    public function __construct(
        Node $node,
                                FindTransactions\RequestFactory $findTransactionsFactory,
                                GetTransactionObjects\ActionFactory $getTransactionObjectsFactory
    ) {
        parent::__construct($node);
        $this->setFindTransactionsFactory($findTransactionsFactory);
        $this->setGetTransactionObjectsFactory($getTransactionObjectsFactory);
    }

    /**
     * Adds a bundle.
     *
     * @param BundleHash $bundleHash
     *
     * @return Action
     */
    public function addBundleHash(BundleHash $bundleHash): self
    {
        $this->bundleHashes[] = $bundleHash;

        return $this;
    }

    /**
     * Overwrites the bundles.
     *
     * @param BundleHash[] $bundleHashes
     *
     * @return Action
     */
    public function setBundleHashes(array $bundleHashes): self
    {
        $this->bundleHashes = [];
        foreach ($bundleHashes as $bundleHash) {
            $this->addBundleHash($bundleHash);
        }

        return $this;
    }

    /**
     * Adds an address.
     *
     * @param Address $address
     *
     * @return Action
     */
    public function addAddress(Address $address): self
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Overwrites the addresses.
     *
     * @param Address[] $addresses
     *
     * @return Action
     */
    public function setAddresses(array $addresses): self
    {
        $this->addresses = [];
        foreach ($addresses as $address) {
            $this->addAddress($address);
        }

        return $this;
    }

    /**
     * Adds a tag.
     *
     * @param Tag $tag
     *
     * @return Action
     */
    public function addTag(Tag $tag): self
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Overwrites the tags.
     *
     * @param Tag[] $tags
     *
     * @return Action
     */
    public function setTags(array $tags): self
    {
        $this->tags = [];
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * Adds an approvee.
     *
     * @param Approvee $approvee
     *
     * @return Action
     */
    public function addApprovee(Approvee $approvee): self
    {
        $this->approvees[] = $approvee;

        return $this;
    }

    /**
     * Overwrites the tags.
     *
     * @param Approvee[] $approvees
     *
     * @return Action
     */
    public function setApprovees(array $approvees): self
    {
        $this->approvees = [];
        foreach ($approvees as $approvee) {
            $this->addApprovee($approvee);
        }

        return $this;
    }

    /**
     * Executes the action.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);

        $findTransactionsResponse = $this->findTransactions(
            $this->node,
            $this->addresses,
            $this->bundleHashes,
            $this->tags,
            $this->approvees
        );
        $result->addChildTrace($findTransactionsResponse->getTrace());

        // TODO: we are just moving things around here
        $getTransactionObjectsResult = $this->getTransactionObjects(
            $this->node,
            $findTransactionsResponse->getTransactionHashes()
        );
        
        $result->addChildTrace($getTransactionObjectsResult->getTrace());
        foreach ($getTransactionObjectsResult->getTransactions() as $transaction) {
            $result->addTransaction($transaction);
        }

        return $result->finish();
    }

    /**
     * Gets the serialized version of the node.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'addresses' => SerializeUtil::serializeArray($this->addresses),
            'bundleHashes' => SerializeUtil::serializeArray($this->bundleHashes),
            'tags' => SerializeUtil::serializeArray($this->tags),
            'approvees' => SerializeUtil::serializeArray($this->approvees),
        ]);
    }
}
