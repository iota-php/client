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

namespace IOTA\ClientApi\Actions\StoreAndBroadcast;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\VoidResult;
use IOTA\Node;
use IOTA\RemoteApi\Actions\BroadcastTransactions;
use IOTA\RemoteApi\Actions\StoreTransactions;
use IOTA\Type\Transaction;
use IOTA\Type\Trytes;
use IOTA\Util\SerializeUtil;

/**
 * Class Action.
 */
class Action extends AbstractAction
{
    use StoreTransactions\ActionTrait,
        BroadcastTransactions\ActionTrait;

    /**
     * Trytes from an attach process.
     *
     * @var Transaction[]
     */
    protected $transactions;

    /**
     * Action constructor.
     *
     * @param Node                                 $node
     * @param StoreTransactions\ActionFactory     $storeTransactionsFactory
     * @param BroadcastTransactions\ActionFactory $broadcastTransactionsFactory
     */
    public function __construct(
        Node $node,
                                StoreTransactions\ActionFactory $storeTransactionsFactory,
                                BroadcastTransactions\ActionFactory $broadcastTransactionsFactory
    ) {
        parent::__construct($node);
        $this->setStoreTransactionsFactory($storeTransactionsFactory);
        $this->setBroadcastTransactionsFactory($broadcastTransactionsFactory);
    }

    /**
     * @param Transaction[] $transactions
     *
     * @return Action
     */
    public function setTransactions(array $transactions): self
    {
        $this->transactions = [];
        foreach ($transactions as $t) {
            $this->addTransaction($t);
        }

        return $this;
    }

    /**
     * Adds trytes.
     *
     * @param Transaction $transaction
     *
     * @return Action
     */
    public function addTransaction(Transaction $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Executes the action.
     *
     * @return VoidResult
     */
    public function execute(): VoidResult
    {
        $result = new VoidResult($this);
        $r1 = $this->storeTransactions($this->node, $this->transactions);
        $result->addChildTrace($r1->getTrace());
        $r2 = $this->broadcastTransactions($this->node, $this->transactions);
        $result->addChildTrace($r2->getTrace());

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactions' => SerializeUtil::serializeArray($this->transactions),
        ]);
    }
}
