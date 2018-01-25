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

namespace IOTA\RemoteApi\Actions\GetBalances;

use IOTA\RemoteApi\AbstractAction;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\Type\Address;
use IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Similar to getInclusionStates. It returns the confirmed balance which a list
 * of addresses have at the latest confirmed milestone. In addition to the
 * balances, it also returns the milestone as well as the index with which the
 * confirmed balance was determined. The balances is returned as a list in the
 * same order as the addresses were provided as input.
 *
 * @see https://iota.readme.io/docs/getbalances
 */
class Action extends AbstractAction
{
    /**
     * List of addresses you want to get the confirmed balance from.
     *
     * @var Address[]
     */
    protected $addresses;

    /**
     * Confirmation threshold, should be set to 100.
     *
     * @var int
     */
    protected $threshold = 100;

    /**
     * Sets the addresses.
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
     * Adds a single address.
     *
     * @param Address $address
     *
     * @return Action
     */
    public function addAddress(Address $address): self
    {
        // @todo refactor it
        /** @var Address $address */
        $address = $address->removeChecksum();
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Gets the addresses.
     *
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * Sets the threshold.
     *
     * @param int $threshold
     *
     * @return Action
     */
    public function setThreshold(int $threshold): self
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * Gets the threshold.
     *
     * @return int
     */
    public function getThreshold(): int
    {
        return $this->threshold;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'getBalances',
            'addresses' => array_map('\strval', $this->addresses),
            'threshold' => $this->threshold,
        ];
    }

    /**
     * Executes the request.
     *
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $response = new Result($this);
        $srvResponse = $this->nodeApiClient->send($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'addresses' => SerializeUtil::serializeArray($this->addresses),
            'threshold' => $this->threshold,
        ]);
    }
}
