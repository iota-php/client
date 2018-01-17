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

namespace Techworker\IOTA\ClientApi\Actions\GetNewAddress;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

class Result extends AbstractResult
{
    /**
     * @var Address
     */
    protected $address;

    /**
     * The list of addresses generated on the way to the new unused address,
     * indexed by the address index.
     *
     * @var Address[]
     */
    protected $passedAddresses = [];

    /**
     * The list of transactions, grouped by the address trytes.
     *
     * @var array
     */
    protected $transactions = [];

    /**
     * Gets the new address.
     *
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * Sets the new Address.
     *
     * @param Address $address
     *
     * @return Result
     */
    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Gets the passed addresses.
     *
     * @return Address[]
     */
    public function getPassedAddresses(): array
    {
        return $this->passedAddresses;
    }

    /**
     * Adds a passed address.
     *
     * @param Address $passedAddress
     * @param int     $index
     *
     * @return Result
     */
    public function addPassedAddress(Address $passedAddress, int $index): self
    {
        $this->passedAddresses[$index] = $passedAddress;

        return $this;
    }

    /**
     * Adds the transactions for the given address.
     *
     * @param Address           $address
     * @param TransactionHash[] ...$transactions
     *
     * @return Result
     */
    public function addTransactions(Address $address, TransactionHash ...$transactions): self
    {
        $this->transactions[(string) $address] = $transactions;

        return $this;
    }

    /**
     * Gets all transactions.
     *
     * @return array
     */
    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Gets the transactions for the given address.
     *
     * @param Address $address
     *
     * @return TransactionHash[]
     */
    public function getTransactions(Address $address): array
    {
        return $this->transactions[(string) $address];
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'address' => $this->address->serialize(),
            'transactions' => array_map(function (array $transactions) {
                return SerializeUtil::serializeArray($transactions);
            }, $this->transactions),
            'passed_addresses' => SerializeUtil::serializeArray($this->passedAddresses),
        ], parent::serialize());
    }
}
