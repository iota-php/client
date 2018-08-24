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

namespace IOTA\ClientApi\Actions\GetNewAddress;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\AbstractResult;
use IOTA\Node;
use IOTA\RemoteApi\Actions\FindTransactions;
use IOTA\Trace;
use IOTA\Type\Address;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;
use IOTA\Util\AddressUtil;

/**
 * class Action.
 *
 * Gets a new unused address that was not used yet for the given seed.
 */
class Action extends AbstractAction
{
    use FindTransactions\ActionTrait;

    /**
     * The seed to derive the addresses from.
     *
     * @var Seed
     */
    protected $seed;

    /**
     * The index to start the generation at.
     *
     * @var int
     */
    protected $startIndex = 0;

    /**
     * The security level for the generated address.
     *
     * @var SecurityLevel
     */
    protected $security;

    /**
     * A flag indicating whether to add a checksum to the address or not.
     *
     * @var bool
     */
    protected $addChecksum;

    /**
     * Address utility to generate an address.
     *
     * @var AddressUtil
     */
    protected $addressUtil;

    /**
     * Action constructor.
     *
     * @param Node                            $node
     * @param AddressUtil                     $addressUtil
     * @param FindTransactions\ActionFactory $findTransactionsFactory
     */
    public function __construct(
        Node $node,
                                AddressUtil $addressUtil,
                                FindTransactions\ActionFactory $findTransactionsFactory
    ) {
        parent::__construct($node);
        $this->addressUtil = $addressUtil;
        $this->setFindTransactionsFactory($findTransactionsFactory);
        $this->security = SecurityLevel::LEVEL_2();
    }

    /**
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
        $this->startIndex = $startIndex;

        return $this;
    }

    /**
     * Gets the security level.
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
     * Sets a value indicating whether the checksum should be added to the
     * address.
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
     * Loops the addresses until one without transactions is found.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        if ($this->startIndex < 0) {
            throw new \InvalidArgumentException('Invalid Index option provided');
        }

        $result = new Result($this);
        $index = $this->startIndex;

        // call findTransactions with each new address to see if the address
        // was already created - if no transaction is found, return the address.
        $address = $transactions = null;
        do {
            if (isset($address, $transactions)) {
                // @var Address $address
                // @var FindTransactions\Response $transactions
                $result->addPassedAddress($address, $index - 1);
                $result->addTransactions($address, ...$transactions->getTransactionHashes());
            }

            $trace = new Trace(AddressUtil::class);
            $trace->start();

            // generate new address
            $address = $this->addressUtil->generateAddress(
                $this->seed,
                $index,
                $this->security,
                $this->addChecksum
            );
            $result->addChildTrace($trace->stop());

            // fetch remotely recorded transactions
            $transactions = $this->findTransactions($this->node, [$address]);
            $result->addChildTrace($transactions->getTrace());
            ++$index;
        } while (\count($transactions->getTransactionHashes()) > 0);

        $result->setAddress($address);

        return $result->finish();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'startIndex' => $this->startIndex,
            'security' => $this->security->serialize(),
            'addChecksum' => $this->addChecksum,
        ]);
    }
}
