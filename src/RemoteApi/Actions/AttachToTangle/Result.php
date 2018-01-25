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

namespace IOTA\RemoteApi\Actions\AttachToTangle;

use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\RemoteApi\AbstractResult;
use IOTA\Type\Transaction;
use IOTA\Util\SerializeUtil;

/**
 * Class Response.
 *
 * A list of altered transaxctions including POW. These are valid transactions
 * which are accepted by the network.
 *
 * @see https://iota.readme.io/docs/attachtotangle
 */
class Result extends AbstractResult
{
    /**
     * The job id.
     *
     * @var string
     */
    protected $id;

    /**
     * The transactions after the POW.
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
     * Gets the transactions.
     *
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Gets the job id if in sandbox mode.
     *
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'id' => $this->id,
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

        if (isset($this->rawData['id'])) {
            $this->id = $this->rawData['id'];
        }
    }
}
