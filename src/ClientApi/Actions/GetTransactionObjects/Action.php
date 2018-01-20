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

namespace Techworker\IOTA\ClientApi\Actions\GetTransactionObjects;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Actions\GetTrytes;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Collects all transaction objects for the given transaction hashes.
 */
class Action extends AbstractAction
{
    use GetTrytes\ActionTrait;

    /**
     * The list of transaction hashes to collect.
     *
     * @var TransactionHash[]
     */
    protected $transactionHashes;

    /**
     * The factory to create a new curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * Action constructor.
     *
     * @param Node                     $node
     * @param GetTrytes\ActionFactory $getTrytesFactory
     * @param CurlFactory              $curlFactory
     */
    public function __construct(Node $node, GetTrytes\ActionFactory $getTrytesFactory, CurlFactory $curlFactory)
    {
        parent::__construct($node);
        $this->setGetTrytesFactory($getTrytesFactory);
        $this->curlFactory = $curlFactory;
    }

    /**
     * Adds a single transaction hash.
     *
     * @param TransactionHash $hash
     *
     * @return Action
     */
    public function addTransactionHash(TransactionHash $hash): self
    {
        $this->transactionHashes[] = $hash;

        return $this;
    }

    /**
     * Sets the transaction hashes.
     *
     * @param TransactionHash[] $transactionHashes
     *
     * @return Action
     */
    public function setTransactionHashes(array $transactionHashes): self
    {
        $this->transactionHashes = [];
        foreach ($transactionHashes as $transactionHash) {
            $this->addTransactionHash($transactionHash);
        }

        return $this;
    }

    /**
     * Executes the action and collects all transactions.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);

        // get the trytes of the transaction hashes
        $response = $this->getTrytes($this->node, $this->transactionHashes);
        $result->addChildTrace($response->getTrace());
        foreach ($response->getTransactions() as $tryte) {
            $result->addTransaction(new Transaction($this->curlFactory, (string) $tryte));
        }

        return $result->finish();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactionHashes' => SerializeUtil::serializeArray($this->transactionHashes),
        ]);
    }
}
