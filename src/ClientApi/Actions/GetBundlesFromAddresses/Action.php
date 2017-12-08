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

namespace Techworker\IOTA\ClientApi\Actions\GetBundlesFromAddresses;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;
use Techworker\IOTA\ClientApi\Actions\GetBundle;
use Techworker\IOTA\ClientApi\Actions\GetLatestInclusion;
use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Bundle;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Replays a transfer by doing Proof of Work again.
 */
class Action extends AbstractAction
{
    use FindTransactionObjects\ActionTrait,
        GetLatestInclusion\ActionTrait,
        GetBundle\ActionTrait;

    /**
     * The list of addresses to search for.
     *
     * @var Address[]
     */
    protected $addresses;

    /**
     * A flag indicating whether to determine the inclusion state.
     *
     * @var bool
     */
    protected $inclusionStates;

    /**
     * Action constructor.
     *
     * @param Node                                 $node
     * @param FindTransactionObjects\ActionFactory $findTransactionObjectsFactory
     * @param GetLatestInclusion\ActionFactory     $getLatestInclusionFactory
     * @param GetBundle\ActionFactory              $getBundleFactory
     */
    public function __construct(
        Node $node,
                                FindTransactionObjects\ActionFactory $findTransactionObjectsFactory,
                                GetLatestInclusion\ActionFactory $getLatestInclusionFactory,
                                GetBundle\ActionFactory $getBundleFactory
    ) {
        $this->setFindTransactionObjectsFactory($findTransactionObjectsFactory);
        $this->setGetLatestInclusionFactory($getLatestInclusionFactory);
        $this->setGetBundleFactory($getBundleFactory);
        parent::__construct($node);
    }

    /**
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

    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;
        return $this;
    }

    /**
     * Sets the value indicating whether to include the inclusion states.
     *
     * @param bool $inclusionStates
     *
     * @return Action
     */
    public function setInclusionStates(bool $inclusionStates): self
    {
        $this->inclusionStates = $inclusionStates;

        return $this;
    }

    /**
     * Executes the action.
     */
    public function execute(): Result
    {
        $result = new Result($this);
        // find all transaction objects from the given addresses
        $findTransactionObjectsResult = $this->findTransactionObjects($this->node, $this->addresses);
        $result->addChildTrace($findTransactionObjectsResult->getTrace());

        $tailTransactions = [];
        $nonTailBundleHashes = [];

        // loop the found transactions and split them into transaction hashes
        // and bundles#
        foreach ($findTransactionObjectsResult->getTransactions() as $transactionObject) {
            if (0 === $transactionObject->getCurrentIndex()) {
                $txHash = (string) $transactionObject->getTransactionHash();
                $tailTransactions[$txHash] = $transactionObject->getTransactionHash();
            } else {
                $bundleHash = (string) $transactionObject->getBundleHash();
                $nonTailBundleHashes[$bundleHash] = $transactionObject->getBundleHash();
            }
        }

        // now we will collect the bundle transactions
        if (\count($nonTailBundleHashes) > 0) {
            $bundleObjects = $this->findTransactionObjects($this->node, [], $nonTailBundleHashes);
            $result->addChildTrace($bundleObjects->getTrace());
            foreach ($bundleObjects->getTransactions() as $bundleObject) {
                if (0 === $bundleObject->getCurrentIndex()) {
                    $bundleHash = (string) $bundleObject->getTransactionHash();
                    $tailTransactions[$bundleHash] = $bundleObject->getTransactionHash();
                }
            }
        }

        $finalBundles = [];
        $tailTxStates = [];
        $tailTxArray = $tailTransactions;
        if ($this->inclusionStates) {
            $tailTxStatesResponse = $this->getLatestInclusion($this->node, $tailTxArray);
            $result->addChildTrace($tailTxStatesResponse->getTrace());
            $tailTxStates = $tailTxStatesResponse->getStates();
        }

        foreach ($tailTxArray as $idx => $tailTx) {
            $bundleResponse = $this->getBundle($this->node, $tailTx);
            $bundle = $bundleResponse->getBundle();
            $result->addChildTrace($bundleResponse->getTrace());
            if ($this->inclusionStates) {
                $isIncluded = (bool) $tailTxStates[(string) $tailTx];
                foreach ($bundle->getTransactions() as $bundleTx) {
                    $bundleTx->setPersistence($isIncluded);
                }
            }
            $finalBundles[] = $bundle;
        }

        usort($finalBundles, function (Bundle $a, Bundle $b) {
            return
                $a->getTransactions()[0]->getTimestamp()
                <=>
                $b->getTransactions()[0]->getTimestamp()
            ;
        });

        $result->setBundles($finalBundles);

        $result->finish();

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'addresses' => SerializeUtil::serializeArray($this->addresses),
            'inclusionStates' => $this->inclusionStates
        ]);
    }
}
