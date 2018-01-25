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

namespace Techworker\IOTA\ClientApi\Actions\SendTransfer;

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\HMACKey;
use Techworker\IOTA\Type\Milestone;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;

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
    private $sendTransferFactory;

    /**
     * @param ActionFactory $sendTransferFactory
     *
     * @return ActionTrait
     */
    protected function setSendTransferFactory(ActionFactory $sendTransferFactory): self
    {
        $this->sendTransferFactory = $sendTransferFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node               $node
     * @param Seed               $seed
     * @param array              $transfers
     * @param int                $minWeightMagnitude
     * @param int                $depth
     * @param null|Address       $remainderAddress
     * @param array              $inputs
     * @param null|HMACKey       $hmacKey
     * @param null|SecurityLevel $security
     * @param null|Milestone     $reference
     *
     * @return Result
     */
    protected function sendTransfer(
        Node $node,
                                    Seed $seed,
                                    array $transfers,
                                    int $minWeightMagnitude,
                                    int $depth,
                                    Address $remainderAddress = null,
                                    array $inputs = [],
                                    HMACKey $hmacKey = null,
                                    SecurityLevel $security = null,
                                    Milestone $reference = null
    ): Result {
        $action = $this->sendTransferFactory->factory($node);
        $action->setSeed($seed);
        $action->setInputs($inputs);
        $action->setTransfers($transfers);
        $action->setMinWeightMagnitude($minWeightMagnitude);
        $action->setDepth($depth);

        if (null !== $remainderAddress) {
            $action->setRemainderAddress($remainderAddress);
        }

        if (null !== $hmacKey) {
            $action->setHmacKey($hmacKey);
        }

        if (null !== $security) {
            $action->setSecurity($security);
        }

        if (null !== $reference) {
            $action->setReference($reference);
        }

        return $action->execute();
    }
}
