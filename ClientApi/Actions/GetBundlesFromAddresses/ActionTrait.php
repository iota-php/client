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

namespace Techworker\IOTA\ClientApi\Actions\GetBundlesFromAddresses;

use Techworker\IOTA\Node;

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
    private $getBundlesFromAddressesFactory;

    /**
     * @param ActionFactory $getBundlesFromAddressesFactory
     *
     * @return ActionTrait
     */
    protected function setGetBundlesFromAddressesFactory(ActionFactory $getBundlesFromAddressesFactory): self
    {
        $this->getBundlesFromAddressesFactory = $getBundlesFromAddressesFactory;

        return $this;
    }

    /**
     * @param Node  $node
     * @param array $addresses
     * @param bool  $inclusionStates
     *
     * @return Result
     */
    protected function getBundlesFromAddresses(
        Node $node,
                                            array $addresses,
                                            bool $inclusionStates = false
    ): Result {
        $action = $this->getBundlesFromAddressesFactory->factory($node);
        $action->setAddresses($addresses);
        $action->setInclusionStates($inclusionStates);

        return $action->execute();
    }
}
