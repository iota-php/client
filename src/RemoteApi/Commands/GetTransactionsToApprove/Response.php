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

namespace Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove;

use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\Type\TransactionHash;

/**
 * Class Response.
 *
 * The raw transaction data (trytes) of a specific transaction. These trytes
 * can then be easily converted into the actual transaction object.
 *
 * @see https://iota.readme.io/docs/gettransactionstoapprove
 */
class Response extends AbstractResponse
{
    /**
     * The trunk transaction to approve.
     *
     * @var TransactionHash
     */
    protected $trunkTransactionHash;

    /**
     * The branch transaction to approve.
     *
     * @var TransactionHash
     */
    protected $branchTransactionHash;

    /**
     * Gets the trunk transaction.
     *
     * @return TransactionHash
     */
    public function getTrunkTransactionHash(): TransactionHash
    {
        return $this->trunkTransactionHash;
    }

    /**
     * Gets the branch transaction.
     *
     * @return TransactionHash
     */
    public function getBranchTransactionHash(): TransactionHash
    {
        return $this->branchTransactionHash;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'trunkTransactionHash' => $this->trunkTransactionHash->serialize(),
            'branchTransactionHash' => $this->branchTransactionHash->serialize(),
        ], parent::serialize());
    }

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['trunkTransaction', 'branchTransaction']);

        $this->trunkTransactionHash = new TransactionHash($this->rawData['trunkTransaction']);
        $this->branchTransactionHash = new TransactionHash($this->rawData['branchTransaction']);
    }
}
