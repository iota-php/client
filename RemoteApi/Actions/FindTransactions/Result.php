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

namespace Techworker\IOTA\RemoteApi\Actions\FindTransactions;

use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Response.
 *
 * The transaction hashes found.
 *
 * @see https://iota.readme.io/docs/findtransactions
 */
class Result extends AbstractResult
{
    /**
     * The list of hashes.
     *
     * @var TransactionHash[]
     */
    protected $transactionHashes;

    /**findTransactions
     * Gets the list of tip hashes.
     *
     * @return TransactionHash[]
     */
    public function getTransactionHashes(): array
    {
        return $this->transactionHashes;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'transactionHashes' => SerializeUtil::serializeArray($this->transactionHashes),
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
        $this->checkRequiredKeys(['hashes']);

        $this->transactionHashes = [];
        // @noinspection ForeachSourceInspection
        foreach ($this->rawData['hashes'] as $transactionHash) {
            $this->transactionHashes[] = new TransactionHash($transactionHash);
        }
    }
}
