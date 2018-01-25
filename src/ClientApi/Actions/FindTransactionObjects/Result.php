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

namespace IOTA\ClientApi\Actions\FindTransactionObjects;

use IOTA\ClientApi\AbstractResult;
use IOTA\Type\Transaction;
use IOTA\Util\SerializeUtil;

/**
 * Contains the found transactions.
 */
class Result extends AbstractResult
{
    /**
     * The list of transactions.
     *
     * @var Transaction[]
     */
    protected $transactions = [];

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
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'transactions' => SerializeUtil::serializeArray($this->transactions),
        ], parent::serialize());
    }
}
