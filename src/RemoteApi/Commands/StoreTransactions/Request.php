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

namespace Techworker\IOTA\RemoteApi\Commands\StoreTransactions;

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Store transactions into the local storage. The trytes to be used for this
 * call are returned by attachToTangle.
 *
 * @see https://iota.readme.io/docs/storetransactions
 */
class Request extends AbstractRequest
{
    /**
     * List of transactions to store.
     *
     * @var Transaction[]
     */
    protected $transactions;

    /**
     * Overwrites all trytes.
     *
     * @param Transaction[] $transactions
     *
     * @return Request
     */
    public function setTransactions(array $transactions): self
    {
        $this->transactions = [];
        foreach ($transactions as $transaction) {
            $this->addTransaction($transaction);
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
     * Gets the list of transactions to store.
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
            'command' => 'storeTransactions',
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

    public function serialize()
    {
        return array_merge(parent::serialize(), [
            'transactions' => SerializeUtil::serializeArray($this->transactions)
        ]);
    }
}
