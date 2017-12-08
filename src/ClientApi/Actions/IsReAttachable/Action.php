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

namespace Techworker\IOTA\ClientApi\Actions\IsReAttachable;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;
use Techworker\IOTA\ClientApi\Actions\GetLatestInclusion;
use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Gets a defined number of addresses for the given seed starting at a
 * specified index.
 */
class Action extends AbstractAction
{
    use FindTransactionObjects\ActionTrait,
        GetLatestInclusion\ActionTrait;

    /**
     * The addresses to check.
     *
     * @var Address[]
     */
    protected $addresses;

    /**
     * Action constructor.
     *
     * @param Node                                 $node
     * @param FindTransactionObjects\ActionFactory $findTransactionObjectsFactory
     * @param GetLatestInclusion\ActionFactory     $getLatestInclusionFactory
     */
    public function __construct(
        Node $node,
                                FindTransactionObjects\ActionFactory $findTransactionObjectsFactory,
                                GetLatestInclusion\ActionFactory $getLatestInclusionFactory
    ) {
        parent::__construct($node);

        $this->setFindTransactionObjectsFactory($findTransactionObjectsFactory);
        $this->setGetLatestInclusionFactory($getLatestInclusionFactory);
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

    /**
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
     * Gets the list of addresses.
     */
    public function execute(): Result
    {
        $result = new Result($this);
        $addressTxsMap = [];

        foreach ($this->addresses as $address) {
            $addressTxsMap[(string) $address] = [];
        }

        $transactions = $this->findTransactionObjects($this->node, $this->addresses);
        $result->addChildTrace($transactions->getTrace());
        $valueTransactions = [];
        foreach ($transactions->getTransactions() as $tx) {
            if ($tx->getValue()->isNeg()) {
                $txAddress = $tx->getAddress();
                $txHash = $tx->getTransactionHash();

                // push hash to map
                $addressTxsMap[$txAddress->__toString()][] = $txHash;

                $valueTransactions[] = $txHash;
            }
        }

        if (\count($valueTransactions) > 0) {
            $inclusionStatesResponse = $this->getLatestInclusion($this->node, $valueTransactions);
            $inclusionStates = $inclusionStatesResponse->getStates();
            $result->addChildTrace($inclusionStatesResponse->getTrace());
            $results = [];
            foreach ($this->addresses as $address) {
                $txs = $addressTxsMap[(string) $address];
                $numTxs = \count($txs);
                if (0 === $numTxs) {
                    continue;
                }

                for ($i = 0; $i < $numTxs; ++$i) {
                    $tx = $txs[$i];

                    $isConfirmed = $inclusionStates[(string) $tx];
                    $shouldReAttach = $isConfirmed ? false : true;

                    // if tx confirmed, break
                    if ($isConfirmed) {
                        break;
                    }

                    $results[(string) $tx] = $shouldReAttach;
                }
            }
        } else {
            $results = [];
            foreach ($this->addresses as $address) {
                $results[(string) $address] = true;
            }
        }

        $result->setStates($results);

        $result->finish();

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'addresses' => SerializeUtil::serializeArray($this->addresses)
        ]);
    }
}
