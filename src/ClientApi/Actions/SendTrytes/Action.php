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

namespace IOTA\ClientApi\Actions\SendTrytes;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\AbstractResult;
use IOTA\ClientApi\Actions\StoreAndBroadcast;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Node;
use IOTA\RemoteApi\Actions\AttachToTangle;
use IOTA\RemoteApi\Actions\GetTransactionsToApprove;
use IOTA\Type\Milestone;
use IOTA\Type\Transaction;
use IOTA\Type\Trytes;
use IOTA\Util\SerializeUtil;

/**
 * Sends the given trytes (transactions) and returns the list of
 * transactions.
 */
class Action extends AbstractAction
{
    use GetTransactionsToApprove\ActionTrait,
        AttachToTangle\ActionTrait,
        StoreAndBroadcast\ActionTrait;

    /**
     * Transactions from an attach process.
     *
     * @var Transaction[]
     */
    protected $transactions;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @var int
     */
    protected $minWeightMagnitude;

    /**
     * @var Milestone
     */
    protected $reference;

    /**
     * The factory to create a new curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * Action constructor.
     *
     * @param Node                                    $node
     * @param GetTransactionsToApprove\ActionFactory $getTransactionsToApproveFactory
     * @param AttachToTangle\ActionFactory           $attachToTangleFactory
     * @param StoreAndBroadcast\ActionFactory         $storeAndBroadcastFactory
     * @param CurlFactory                             $curlFactory
     */
    public function __construct(
        Node $node,
        GetTransactionsToApprove\ActionFactory $getTransactionsToApproveFactory,
        AttachToTangle\ActionFactory $attachToTangleFactory,
        StoreAndBroadcast\ActionFactory $storeAndBroadcastFactory,
        CurlFactory $curlFactory
    ) {
        parent::__construct($node);
        $this->setGetTransactionsToApproveFactory($getTransactionsToApproveFactory);
        $this->setAttachToTangleFactory($attachToTangleFactory);
        $this->setStoreAndBroadcastFactory($storeAndBroadcastFactory);
        $this->curlFactory = $curlFactory;
    }

    /**
     * @param Transaction[] $transactions
     *
     * @return Action
     */
    public function setTransactions(array $transactions): self
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
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
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);
        // fetch transactions to approve
        $transactionsToApprove = $this->getTransactionsToApprove(
            $this->node,
            $this->depth,
            null,
            $this->reference
        );

        $result->addChildTrace($transactionsToApprove->getTrace());
        $result->setTrunkTransactionHash($transactionsToApprove->getTrunkTransactionHash());
        $result->setBranchTransactionHash($transactionsToApprove->getBranchTransactionHash());

        // attach them to the tangle
        $attachedResponse = $this->attachToTangle(
            $this->node,
            $this->transactions,
            $transactionsToApprove->getTrunkTransactionHash(),
            $transactionsToApprove->getBranchTransactionHash(),
            $this->minWeightMagnitude
        );
        $result->addChildTrace($attachedResponse->getTrace());

        // store and broadcast the transaction.
        $storeAndBroadcastResponse = $this->storeAndBroadcast(
            $this->node,
            $attachedResponse->getTransactions()
        );
        $result->addChildTrace($storeAndBroadcastResponse->getTrace());

        // collect the final transactions.
        foreach ($attachedResponse->getTransactions() as $tryte) {
            $result->addTransaction(new Transaction($this->curlFactory, (string) $tryte));
        }

        return $result->finish();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactions' => SerializeUtil::serializeArray($this->transactions),
            'depth' => $this->depth,
            'minWeightMagnitude' => $this->minWeightMagnitude,
            'reference' => null === $this->reference ? null : $this->reference->serialize(),
        ]);
    }
}
