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

namespace IOTA\RemoteApi\Actions\GetTrytes;

use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Node;
use IOTA\RemoteApi\AbstractAction;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\RemoteApi\NodeApiClient;
use IOTA\Type\TransactionHash;
use IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Returns the raw transaction data (trytes) of a specific transaction. These
 * trytes can then be easily converted into the actual transaction object. See
 * utility functions for more details.
 *
 * @see https://iota.readme.io/docs/gettrytes
 */
class Action extends AbstractAction
{
    /**
     * List of transaction hashes of which you want to get trytes from.
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
     * Request constructor.
     *
     * @param NodeApiClient $httpClient
     * @param CurlFactory         $curlFactory
     * @param Node                $node
     */
    public function __construct(NodeApiClient $httpClient, CurlFactory $curlFactory, Node $node)
    {
        $this->curlFactory = $curlFactory;
        parent::__construct($httpClient, $node);
    }

    /**
     * Sets the transaction hashes.
     *
     * @param array $transactionHashes
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
     * Adds a single transaction hash.
     *
     * @param TransactionHash $transactionHash
     *
     * @return Action
     */
    public function addTransactionHash(TransactionHash $transactionHash): self
    {
        $this->transactionHashes[] = $transactionHash;

        return $this;
    }

    /**
     * Gets the list of transaction hashes.
     *
     * @return TransactionHash[]
     */
    public function getTransactionHashes(): array
    {
        return $this->transactionHashes;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'getTrytes',
            'hashes' => array_map('\strval', $this->transactionHashes),
        ];
    }

    /**
     * Executes the request.
     *
     * @throws Exception
     *
     * @return Result|AbstractResult
     */
    public function execute(): Result
    {
        $response = new Result($this->curlFactory, $this);
        $srvResponse = $this->nodeApiClient->send($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactionHashes' => SerializeUtil::serializeArray($this->transactionHashes),
        ]);
    }
}
