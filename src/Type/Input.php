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

namespace IOTA\Type;

use IOTA\SerializeInterface;

/**
 * Class Input.
 *
 * Input informations.
 */
class Input implements SerializeInterface
{
    /**
     * The input address.
     *
     * @var Address
     */
    protected $address;

    /**
     * The iota balance.
     *
     * @var Iota
     */
    protected $balance;

    /**
     * The address index.
     *
     * @var int
     */
    protected $index;

    /**
     * The security level.
     *
     * @var SecurityLevel
     */
    protected $security;

    /**
     * Input constructor.
     *
     * @param Address       $address
     * @param Iota          $balance
     * @param int           $index
     * @param SecurityLevel $security
     */
    public function __construct(Address $address, Iota $balance, $index, SecurityLevel $security)
    {
        $this->address = $address;
        $this->balance = $balance;
        $this->index = $index;
        $this->security = $security;
    }

    /**
     * Gets the address of the input.
     *
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * Gets the balance of the input.
     *
     * @return Iota
     */
    public function getBalance(): Iota
    {
        return $this->balance;
    }

    /**
     * Gets the index of the address.
     *
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * Gets the security level.
     *
     * @return SecurityLevel
     */
    public function getSecurity(): SecurityLevel
    {
        return $this->security;
    }

    /**
     * Gets the balance of the address.
     *
     * @param Iota $balance
     *
     * @return Input
     */
    public function setBalance(Iota $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Gets the array representation of the class.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'address' => $this->address->serialize(),
            'security' => $this->security->serialize(),
            'index' => $this->index,
            'balance' => $this->balance->serialize(),
        ];
    }
}
