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

namespace IOTA\ClientApi\Actions\GetInputs;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\Actions\GetAddresses;
use IOTA\ClientApi\Actions\GetNewAddress;
use IOTA\Node;
use IOTA\RemoteApi\Actions\GetBalances;
use IOTA\Type\Input;
use IOTA\Type\Iota;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;
use IOTA\Util\AddressUtil;

/**
 * Replays a transfer by doing Proof of Work again.
 */
class Action extends AbstractAction
{
    use GetAddresses\ActionTrait,
        GetNewAddress\ActionTrait,
        GetBalances\ActionTrait;

    /**
     * @var Seed
     */
    protected $seed;

    /**
     * @var int
     */
    protected $startIndex = 0;

    /**
     * @var int
     */
    protected $endIndex = -1;

    /**
     * @var Iota
     */
    protected $threshold;

    /**
     * @var SecurityLevel
     */
    protected $security;

    /**
     * @var AddressUtil
     */
    protected $addressUtil;

    /**
     * Action constructor.
     *
     * @param Node                        $node
     * @param GetAddresses\ActionFactory  $getAddressesFactory
     * @param GetNewAddress\ActionFactory $getNewAddressFactory
     * @param GetBalances\ActionFactory  $getBalancesFactory
     */
    public function __construct(
        Node $node,
                                GetAddresses\ActionFactory $getAddressesFactory,
                                GetNewAddress\ActionFactory $getNewAddressFactory,
                                GetBalances\ActionFactory $getBalancesFactory
    ) {
        parent::__construct($node);
        $this->security = SecurityLevel::LEVEL_2();
        $this->setGetAddressesFactory($getAddressesFactory);
        $this->setGetNewAddressFactory($getNewAddressFactory);
        $this->setGetBalancesFactory($getBalancesFactory);
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
     * @param int $endIndex
     *
     * @return Action
     */
    public function setEndIndex(int $endIndex): self
    {
        $this->endIndex = $endIndex;

        return $this;
    }

    /**
     * @param Iota $threshold
     *
     * @return Action
     */
    public function setThreshold(Iota $threshold): self
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
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
     * Executes the action.
     *
     * @return Result
     */
    public function execute(): Result
    {
        $result = new Result($this);

        if ($this->endIndex !== -1) {
            // TODO: why 500?
            if ($this->startIndex > $this->endIndex || $this->endIndex > ($this->startIndex + 500)) {
                throw new \InvalidArgumentException('Invalid inputs provided');
            }

            $addressesResponse = $this->getAddresses(
                $this->node,
                $this->seed,
                $this->startIndex,
                $this->endIndex - $this->startIndex,
                false,
                $this->security
            );
            $result->addChildTrace($addressesResponse->getTrace());
            $addresses = $addressesResponse->getAddresses();
        } else {
            $res = $this->getNewAddress($this->node, $this->seed, $this->startIndex, false, $this->security);
            $result->addChildTrace($res->getTrace());
            $addresses = array_values($res->getPassedAddresses());
            $addresses[] = $res->getAddress();
        }

        $this->getBalanceAndFormat($addresses, $result);

        $result->finish();

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'seed' => $this->seed->serialize(),
            'startIndex' => $this->startIndex,
            'endIndex' => $this->endIndex,
            'threshold' => $this->threshold->serialize(),
            'security' => $this->security->serialize(),
        ]);
    }

    /**
     * @param array  $addresses
     * @param Result $result
     */
    protected function getBalanceAndFormat(array $addresses, Result $result): void
    {
        $result->setBalance(Iota::ZERO());
        $balances = $this->getBalances($this->node, $addresses);
        $result->addChildTrace($balances->getTrace());
        $thresholdReached = (null !== $this->threshold);
        $length = \count($addresses);
        for ($i = 0; $i < $length; ++$i) {
            $balance = new Iota($balances->getBalances()[(string) $addresses[$i]]);
            if ($balance->isPos()) {
                $input = new Input($addresses[$i], $balance, $this->startIndex + $i, $this->security);

                $result->addInput($input);
                $result->setBalance($result->getBalance()->plus($balance));
                if (null !== $this->threshold && $result->getBalance()->gteq($this->threshold)) {
                    $thresholdReached = true;

                    break;
                }
            }
        }

        if ($thresholdReached) {
            return;
        }
    }
}
