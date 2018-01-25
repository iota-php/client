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

namespace IOTA\ClientApi\Actions\GetTransfers;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\AbstractResult;
use IOTA\ClientApi\Actions\GetBundlesFromAddresses;
use IOTA\ClientApi\Actions\GetNewAddress;
use IOTA\Node;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;

/**
 * Gets a defined number of addresses for the given seed starting at a
 * specified index.
 */
class Action extends AbstractAction
{
    use GetNewAddress\ActionTrait,
        GetBundlesFromAddresses\ActionTrait;

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
    protected $inclusionStates = false;

    /**
     * Action constructor.
     *
     * @param Node                                  $node
     * @param GetNewAddress\ActionFactory           $getNewAddressFactory
     * @param GetBundlesFromAddresses\ActionFactory $getBundlesFromAddressesFactory
     */
    public function __construct(
        Node $node,
                                GetNewAddress\ActionFactory $getNewAddressFactory,
                                GetBundlesFromAddresses\ActionFactory $getBundlesFromAddressesFactory
    ) {
        parent::__construct($node);
        $this->setGetNewAddressFactory($getNewAddressFactory);
        $this->setGetBundlesFromAddressesFactory($getBundlesFromAddressesFactory);
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
     * @return bool
     */
    public function isInclusionStates(): bool
    {
        return $this->inclusionStates;
    }

    /**
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
     * Gets the list of addresses.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);
        $res = $this->getNewAddress($this->node, $this->seed, $this->startIndex, false, $this->security);
        $result->addChildTrace($res->getTrace());
        $addresses = $res->getPassedAddresses();
        $addresses[] = $res->getAddress();

        $bundles = $this->getBundlesFromAddresses($this->node, $addresses, $this->inclusionStates);
        $result->addChildTrace($bundles->getTrace());
        $result->fromResult($bundles);
        $result->finish();

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'seed' => $this->seed->serialize(),
            'startIndex' => $this->startIndex,
            'security' => $this->security->serialize(),
            'inclusionStates' => $this->inclusionStates,
        ]);
    }
}
