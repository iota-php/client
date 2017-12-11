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

namespace Techworker\IOTA\RemoteApi\Commands\GetTrytes;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Returns the raw transaction data (trytes) of a specific transaction. These
 * trytes can then be easily converted into the actual transaction object. See
 * utility functions for more details.
 *
 * @see https://iota.readme.io/docs/gettrytes
 */
class Request extends AbstractRequest
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
     * @param HttpClientInterface $httpClient
     * @param CurlFactory $curlFactory
     * @param Node $node
     */
    public function __construct(HttpClientInterface $httpClient, CurlFactory $curlFactory, Node $node)
    {
        $this->curlFactory = $curlFactory;
        parent::__construct($httpClient, $node);
    }


    /**
     * Sets the transaction hashes.
     *
     * @param array $transactionHashes
     * @return Request
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
     * @return Request
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
     * @return AbstractResponse|Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $response = new Response($this->curlFactory, $this);
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    public function serialize() : array
    {
        return array_merge(parent::serialize(), [
            'transactionHashes' => SerializeUtil::serializeArray($this->transactionHashes)
        ]);
    }
}
