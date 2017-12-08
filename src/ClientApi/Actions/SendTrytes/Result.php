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

namespace Techworker\IOTA\ClientApi\Actions\SendTrytes;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

class Result extends AbstractResult
{
    /**
     * The list of transactions.
     *
     * @var Transaction[]
     */
    protected $transactions = [];

    /**
     * The approved trunk transaction.
     *
     * @var TransactionHash
     */
    protected $trunkTransactionHash;

    /**
     * The approved branch transaction.
     *
     * @var TransactionHash
     */
    protected $branchTransactionHash;

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
     * Adds a transaction.
     *
     * @param Transaction $transaction
     *
     * @return Result
     */
    public function addTransaction(Transaction $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * @param TransactionHash $trunkTransactionHash
     *
     * @return Result
     */
    public function setTrunkTransactionHash(TransactionHash $trunkTransactionHash): self
    {
        $this->trunkTransactionHash = $trunkTransactionHash;

        return $this;
    }

    /**
     * @return TransactionHash
     */
    public function getTrunkTransactionHash(): TransactionHash
    {
        return $this->trunkTransactionHash;
    }

    /**
     * @param TransactionHash $branchTransactionHash
     *
     * @return Result
     */
    public function setBranchTransactionHash(TransactionHash $branchTransactionHash): self
    {
        $this->branchTransactionHash = $branchTransactionHash;

        return $this;
    }

    /**
     * @return TransactionHash
     */
    public function getBranchTransactionHash(): TransactionHash
    {
        return $this->branchTransactionHash;
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'transactions' => SerializeUtil::serializeArray($this->transactions),
            'trunkTransactionHash' => $this->trunkTransactionHash->serialize(),
            'branchTransactionHash' => $this->branchTransactionHash->serialize(),
        ], parent::serialize());
    }
}
