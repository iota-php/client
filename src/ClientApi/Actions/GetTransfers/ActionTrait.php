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

use IOTA\ClientApi\Actions\GetBundlesFromAddresses;
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
    private $getTransfersFactory;

    /**
     * @param ActionFactory $getTransfersFactory
     *
     * @return ActionTrait
     */
    protected function setGetTransfersFactory(ActionFactory $getTransfersFactory): self
    {
        $this->getTransfersFactory = $getTransfersFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node               $node
     * @param Seed               $seed
     * @param int                $startIndex
     * @param bool               $inclusionStates
     * @param null|SecurityLevel $security
     *
     * @return GetBundlesFromAddresses\Result
     */
    protected function getTransfers(
        Node $node,
                                    Seed $seed,
                                    int $startIndex = 0,
                                    bool $inclusionStates = false,
                                    SecurityLevel $security = null
    ): GetBundlesFromAddresses\Result {
        $action = $this->getTransfersFactory->factory($node);
        $action->setSeed($seed);
        $action->setStartIndex($startIndex);
        $action->setInclusionStates($inclusionStates);
        if (null !== $security) {
            $action->setSecurity($security);
        }

        return $action->execute();
    }
}
