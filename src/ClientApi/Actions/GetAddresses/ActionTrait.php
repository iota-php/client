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

use IOTA\Node;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;

/**
 * Replays a transfer by doing Proof of Work again.
 */
trait ActionTrait
{
    /**
     * The action factory.
     *
     * @var ActionFactory
     */
    private $getAddressesFactory;

    /**
     * @param ActionFactory $getAddressesFactory
     *
     * @return ActionTrait
     */
    protected function setGetAddressesFactory(ActionFactory $getAddressesFactory): self
    {
        $this->getAddressesFactory = $getAddressesFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node               $node
     * @param Seed               $seed
     * @param int                $startIndex
     * @param int                $amount
     * @param bool               $addChecksum
     * @param null|SecurityLevel $security
     *
     * @return Result
     */
    protected function getAddresses(
        Node $node,
                                      Seed $seed,
                                      int $startIndex = 0,
                                      int $amount = 1,
                                      bool $addChecksum = false,
                                      SecurityLevel $security = null
    ): Result {
        $action = $this->getAddressesFactory->factory($node);
        $action->setSeed($seed);
        $action->setStartIndex($startIndex);
        if (null !== $security) {
            $action->setSecurity($security);
        }
        $action->setAddChecksum($addChecksum);
        $action->setAmount($amount);

        return $action->execute();
    }
}
