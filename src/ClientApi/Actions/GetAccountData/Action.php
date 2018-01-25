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

namespace IOTA\ClientApi\Actions\GetAccountData;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\Actions\GetBundlesFromAddresses;
use IOTA\ClientApi\Actions\GetNewAddress;
use IOTA\Node;
use IOTA\RemoteApi\Actions\GetBalances;
use IOTA\Type\AccountData;
use IOTA\Type\Input;
use IOTA\Type\Iota;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;

/**
 * Gets a defined number of addresses for the given seed starting at a
 * specified index.
 */
class Action extends AbstractAction
{
    use GetNewAddress\ActionTrait,
        GetBundlesFromAddresses\ActionTrait,
        GetBalances\ActionTrait;

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
     * Action constructor.
     *
     * @param Node                                  $node
     * @param GetNewAddress\ActionFactory           $getNewAddressFactory
     * @param GetBundlesFromAddresses\ActionFactory $getBundlesFromAddressesFactory
     * @param GetBalances\ActionFactory            $getBalancesFactory
     */
    public function __construct(
        Node $node,
                                GetNewAddress\ActionFactory $getNewAddressFactory,
                                GetBundlesFromAddresses\ActionFactory $getBundlesFromAddressesFactory,
                                GetBalances\ActionFactory $getBalancesFactory
    ) {
        parent::__construct($node);
        $this->setGetNewAddressFactory($getNewAddressFactory);
        $this->setGetBundlesFromAddressesFactory($getBundlesFromAddressesFactory);
        $this->setGetBalancesFactory($getBalancesFactory);
        $this->security = SecurityLevel::LEVEL_2();
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
     * @return Result
     */
    public function execute(): Result
    {
        $result = new Result($this);

        // create new account data object
        $accountData = new AccountData();

        $getNewAddressResult = $this->getNewAddress(
            $this->node,
            $this->seed,
            $this->startIndex,
            false,
            $this->security
        );

        $result->addChildTrace($getNewAddressResult->getTrace());
        $addresses = $getNewAddressResult->getPassedAddresses();
        $addresses[] = $getNewAddressResult->getAddress();

        $accountData->setLatestUnusedAddress($getNewAddressResult->getAddress());
        foreach (\array_slice($addresses, 0, -1) as $address) {
            $accountData->addAddress($address);
        }

        $bundlesFromAddressesResult = $this->getBundlesFromAddresses($this->node, $addresses);
        $result->addChildTrace($bundlesFromAddressesResult->getTrace());
        foreach ($bundlesFromAddressesResult->getBundles() as $bundle) {
            $accountData->addBundle($bundle);
        }

        $getBalancesResponse = $this->getBalances($this->node, $accountData->getAddresses());
        $result->addChildTrace($getBalancesResponse->getTrace());
        foreach ($getBalancesResponse->getBalances() as $index => $balance) {
            $balance = new Iota((string) $balance);
            $accountData->setBalance($accountData->getBalance()->plus($balance));

            if ($balance->isPos()) {
                $input = new Input($accountData->getAddresses()[$index], $balance, $index, $this->security);
                $accountData->addInput($input);
            }
        }

        $result->setAccountData($accountData);

        $result->finish();

        return $result;
    }

    /**
     * Gets the serialized version of the action.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'seed' => $this->seed->serialize(),
            'startIndex' => $this->startIndex,
            'security' => $this->security->serialize(),
        ]);
    }
}
