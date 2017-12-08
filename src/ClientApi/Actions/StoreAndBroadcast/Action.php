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

namespace Techworker\IOTA\ClientApi\Actions\StoreAndBroadcast;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\VoidResult;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions;
use Techworker\IOTA\RemoteApi\Commands\StoreTransactions;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\Trytes;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Action.
 */
class Action extends AbstractAction
{
    use StoreTransactions\RequestTrait,
        BroadcastTransactions\RequestTrait;

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
     * @param StoreTransactions\RequestFactory     $storeTransactionsFactory
     * @param BroadcastTransactions\RequestFactory $broadcastTransactionsFactory
     */
    public function __construct(
        Node $node,
                                StoreTransactions\RequestFactory $storeTransactionsFactory,
                                BroadcastTransactions\RequestFactory $broadcastTransactionsFactory
    ) {
        parent::__construct($node);
        $this->setStoreTransactionsFactory($storeTransactionsFactory);
        $this->setBroadcastTransactionsFactory($broadcastTransactionsFactory);
    }

    /**
     * @param Trytes[] $transactions
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
            'transactions' => SerializeUtil::serializeArray($this->transactions)
        ]);
    }
}
