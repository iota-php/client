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

namespace Techworker\IOTA\RemoteApi\Actions\GetTrytes;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Response.
 *
 * The raw transaction data (trytes) of a specific transaction. These trytes
 * can then be easily converted into the actual transaction object.
 *
 * @see https://iota.readme.io/docs/gettrytes
 */
class Result extends AbstractResult
{
    /**
     * The list of hashes.
     *
     * @var Transaction[]
     */
    protected $transactions;

    /**
     * The factory to create a new curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * Response constructor.
     *
     * @param CurlFactory $curlFactory
     * @param Action     $request
     */
    public function __construct(CurlFactory $curlFactory, Action $request)
    {
        parent::__construct($request);
        $this->curlFactory = $curlFactory;
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
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'transactions' => SerializeUtil::serializeArray($this->transactions),
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
        $this->checkRequiredKeys(['trytes']);

        $this->transactions = [];
        // @noinspection ForeachSourceInspection
        foreach ($this->rawData['trytes'] as $transaction) {
            $this->transactions[] = new Transaction($this->curlFactory, $transaction);
        }
    }
}
