<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\ClientApi\Actions\SendTransfer;

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Type\Trytes;

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
     * @param Node $node
     * @param Seed $seed
     * @param array $transfers
     * @param int $minWeightMagnitude
     * @param int $depth
     * @param bool $ignoreSpamTransactions
     * @param Address|null $remainderAddress
     * @param array $inputs
     * @param Trytes|null $hmacKey
     * @param SecurityLevel|null $security
     * @return Result
     */
    protected function sendTransfer(
        Node $node,
                                    Seed $seed,
                                    array $transfers,
                                    int $minWeightMagnitude,
                                    int $depth,
                                    bool $ignoreSpamTransactions = false,
                                    Address $remainderAddress = null,
                                    array $inputs = [],
                                    Trytes $hmacKey = null,
                                    SecurityLevel $security = null
    ): Result {
        $action = $this->sendTransferFactory->factory($node);
        $action->setSeed($seed);
        $action->setInputs($inputs);
        $action->setTransfers($transfers);
        $action->setMinWeightMagnitude($minWeightMagnitude);
        $action->setDepth($depth);
        $action->setIgnoreSpamTransactions($ignoreSpamTransactions);

        if (null !== $remainderAddress) {
            $action->setRemainderAddress($remainderAddress);
        }

        if (null !== $hmacKey) {
            $action->setHmacKey($hmacKey);
        }

        if (null !== $security) {
            $action->setSecurity($security);
        }

        return $action->execute();
    }
}
