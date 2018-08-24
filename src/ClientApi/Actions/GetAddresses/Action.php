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

namespace IOTA\ClientApi\Actions\GetAddresses;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\AbstractResult;
use IOTA\Node;
use IOTA\Trace;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;
use IOTA\Util\AddressUtil;

/**
 * Gets a defined number of addresses for the given seed starting at a
 * specified index.
 */
class Action extends AbstractAction
{
    /**
     * The seed.
     *
     * @var Seed
     */
    protected $seed;

    /**
     * The start index.
     *
     * @var int
     */
    protected $startIndex = 0;

    /**
     * The level of security.
     *
     * @var SecurityLevel
     */
    protected $security;

    /**
     * A value indicating whether to add a checksum to the addresses.
     *
     * @var bool
     */
    protected $addChecksum = false;

    /**
     * Address utility.
     *
     * @var AddressUtil
     */
    protected $addressUtil;

    /**
     * The number of addresses to return.
     *
     * @var int
     */
    protected $amount = 1;

    /**
     * Action constructor.
     *
     * @param Node        $node
     * @param AddressUtil $addressUtil
     */
    public function __construct(Node $node, AddressUtil $addressUtil)
    {
        $this->addressUtil = $addressUtil;
        $this->security = SecurityLevel::LEVEL_2();
        parent::__construct($node);
    }

    /**
     * Sets the seed.
     *
     * @param Seed $seed
     *
     * @return Action
     */
    public function setSeed(Seed $seed): self
    {
        $this->seed = $seed;

        return $this;
    }

    /**
     * Sets the start index.
     *
     * @param int $startIndex
     *
     * @return Action
     */
    public function setStartIndex(int $startIndex): self
    {
        if ($startIndex < 0) {
            throw new \InvalidArgumentException('Invalid Index option provided');
        }

        $this->startIndex = $startIndex;

        return $this;
    }

    /**
     * Sets the security level.
     *
     * @param SecurityLevel $security
     *
     * @return Action
     */
    public function setSecurity(SecurityLevel $security): self
    {
        $this->security = $security;

        return $this;
    }

    /**
     * Sets the value indicating whether the checksum should be added to the
     * addresses.
     *
     * @param bool $addChecksum
     *
     * @return Action
     */
    public function setAddChecksum(bool $addChecksum): self
    {
        $this->addChecksum = $addChecksum;

        return $this;
    }

    /**
     * Sets the amount of addresses to be generated.
     *
     * @param int $amount
     *
     * @return Action
     */
    public function setAmount(int $amount): self
    {
        if ($amount < 1) {
            throw new \InvalidArgumentException('Invalid amount provided');
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * Gets the list of addresses.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);
        $index = $this->startIndex; // don't change the state

        for ($i = 0; $i < $this->amount; ++$i) {
            $trace = new Trace(AddressUtil::class);
            $trace->start();
            $address = $this->addressUtil->generateAddress(
                $this->seed,
                $index,
                $this->security,
                $this->addChecksum
            );
            $result->addAddress($address, $index);
            $result->addChildTrace($trace->stop());
            ++$index;
        }

        return $result->finish();
    }

    /**
     * Gets the serialized version of the action.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'startIndex' => $this->startIndex,
            'security' => $this->security->serialize(),
            'addChecksum' => $this->addChecksum,
        ]);
    }
}
