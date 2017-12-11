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

namespace Techworker\IOTA\Type;

use Techworker\IOTA\SerializeInterface;

/**
 * Class AccountData
 *
 * Collection of data for an account.
 */
class AccountData implements SerializeInterface
{
    /**
     * The list of addresses.
     *
     * @var Address[]
     */
    protected $addresses;

    /**
     * The total balance of the account.
     *
     * @var Iota
     */
    protected $balance;

    /**
     * The latest used address.
     *
     * @var Address
     */
    protected $latestUnusedAddress;

    /**
     * The list of bundles for the account.
     *
     * @var Bundle[]
     */
    protected $bundles;

    /**
     * The list of inputs.
     *
     * @var Input[]
     */
    protected $inputs;

    /**
     * AccountData constructor.
     */
    public function __construct()
    {
        $this->inputs = [];
        $this->addresses = [];
        $this->balance = Iota::ZERO();
        $this->bundles = [];
    }

    /**
     * Adds an address to the list of addresses.
     *
     * @param Address $address
     *
     * @return AccountData
     */
    public function addAddress(Address $address): self
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Gets the list of addresses associated with the account.
     *
     * @return array
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * Adds a bundle.
     *
     * @param Bundle $bundle
     *
     * @return AccountData
     */
    public function addBundle(Bundle $bundle): self
    {
        $this->bundles[] = $bundle;

        return $this;
    }

    /**
     * Gets the list of bundles.
     *
     * @return Bundle[]
     */
    public function getBundles(): array
    {
        return $this->bundles;
    }

    /**
     * Gets the balance.
     *
     * @return Iota
     */
    public function getBalance(): Iota
    {
        return $this->balance;
    }

    /**
     * Sets the balance.
     *
     * @param Iota $balance
     *
     * @return AccountData
     */
    public function setBalance(Iota $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Gets the latest address.
     *
     * @return Address
     */
    public function getLatestUnusedAddress(): Address
    {
        return $this->latestUnusedAddress;
    }

    /**
     * Sets the latest address.
     *
     * @param Address $latestUnusedAddress
     *
     * @return AccountData
     */
    public function setLatestUnusedAddress(Address $latestUnusedAddress): self
    {
        $this->latestUnusedAddress = $latestUnusedAddress;

        return $this;
    }

    /**
     * Adds a single input.
     *
     * @param Input $input
     * @return AccountData
     */
    public function addInput(Input $input) : self
    {
        $this->inputs[] = $input;

        return $this;
    }

    /**
     * Gets the array version of the account data.
     *
     * @return array
     */
    public function serialize() : array
    {
        return [
            'addresses' => array_map(function (Address $address) {
                return $address->serialize();
            }, $this->addresses),
            'balance' => $this->balance->getAmount(),
            'latestUnusedAddress' => (string) $this->latestUnusedAddress,
            'bundles' => array_map(function (Bundle $bundle) {
                return $bundle->serialize();
            }, $this->bundles),
            'inputs' => array_map(function (Input $input) {
                return $input->serialize();
            }, $this->inputs),
        ];
    }
}
