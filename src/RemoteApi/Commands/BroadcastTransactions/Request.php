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

namespace Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions;

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\Trytes;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Broadcasts a list of transactions to all neighbors. The transactions for this
 * call are provided by the `attachToTangle` endpoint.
 *
 * @link https://iota.readme.io/docs/broadcasttransactions
 */
class Request extends AbstractRequest
{
    /**
     * List transactions to be rebroadcast.
     *
     * @var Transaction[]
     */
    protected $transactions;

    /**
     * Overwrites all transactions.
     *
     * @param Trytes[] $transactions
     *
     * @return Request
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
     * Adds a single transaction.
     *
     * @param Transaction $transaction
     *
     * @return $this
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Gets the list of transactions.
     *
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'broadcastTransactions',
            'trytes' => array_map('\strval', $this->transactions),
        ];
    }

    /**
     * Executes the request.
     *
     * @return AbstractResponse|Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $response = new Response($this);
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    /**
     * Gets the array representation of the request.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactions' => SerializeUtil::serializeArray($this->transactions)
        ]);
    }
}
