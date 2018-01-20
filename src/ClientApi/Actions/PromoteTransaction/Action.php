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

namespace Techworker\IOTA\ClientApi\Actions\PromoteTransaction;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\ClientApi\Actions\SendTransfer;
use Techworker\IOTA\Exception;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Actions\IsTailConsistent;
use Techworker\IOTA\Type\Milestone;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Type\Transfer;

/**
 * TODO.
 */
class Action extends AbstractAction
{
    use IsTailConsistent\ActionTrait,
        SendTransfer\ActionTrait;

    /**
     * Tail transaction hash.
     *
     * @var TransactionHash
     */
    protected $tailTransactionHash;

    /**
     * The depth to replay.
     *
     * @var int
     */
    protected $depth;

    /**
     * The weight magnitude.
     *
     * @var int
     */
    protected $minWeightMagnitude;

    /**
     * The transfer.
     *
     * @var Transfer
     */
    protected $transfer;

    /**
     * The reference.
     *
     * @var Milestone
     */
    protected $reference;

    /**
     * Action constructor.
     *
     * @param Node                            $node
     * @param IsTailConsistent\ActionFactory $isTailConsistentFactory
     * @param SendTransfer\ActionFactory      $sendTransferFactory
     */
    public function __construct(
        Node $node,
        IsTailConsistent\ActionFactory $isTailConsistentFactory,
        SendTransfer\ActionFactory $sendTransferFactory
    ) {
        parent::__construct($node);
        $this->setIsTailConsistentFactory($isTailConsistentFactory);
        $this->setSendTransferFactory($sendTransferFactory);
    }

    /**
     * Sets the hash of the tail transaction.
     *
     * @param TransactionHash $tailTransactionHash
     *
     * @return Action
     */
    public function setTailTransactionHash(TransactionHash $tailTransactionHash): self
    {
        $this->tailTransactionHash = $tailTransactionHash;

        return $this;
    }

    /**
     * Sets the depth.
     *
     * @param int $depth
     *
     * @return Action
     */
    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Sets the min weight magnitude.
     *
     * @param int $minWeightMagnitude
     *
     * @return Action
     */
    public function setMinWeightMagnitude(int $minWeightMagnitude): self
    {
        $this->minWeightMagnitude = $minWeightMagnitude;

        return $this;
    }

    /**
     * @param Transfer $transfer
     *
     * @return Action
     */
    public function setTransfer(Transfer $transfer): self
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @param Milestone $reference
     *
     * @return Action
     */
    public function setReference(Milestone $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Executes the action.
     *
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);

        $isTailConsistentResult = $this->isTailConsistent($this->node, $this->reference);
        $result->addChildTrace($isTailConsistentResult->getTrace());
        if (false === $isTailConsistentResult->getState()) {
            throw new Exception('Inconsistent subtangle: '.$this->reference);
        }

        $sendTransferResult = $this->sendTransfer(
            $this->node,
            new Seed((string) $this->transfer->getRecipientAddress()->removeChecksum()),
            [$this->transfer],
            $this->minWeightMagnitude,
            $this->depth,
            true,
            null,
            [],
            null,
            null,
            $this->reference
        ); // uah!
        $result->setSendTransferResult($sendTransferResult);

        $result->finish();

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'tailTransactionHash' => $this->tailTransactionHash->serialize(),
            'depth' => $this->depth,
            'minWeightMagnitude' => $this->minWeightMagnitude,
            'transfer' => $this->transfer->serialize(),
            'reference' => $this->reference->serialize(),
        ]);
    }
}
