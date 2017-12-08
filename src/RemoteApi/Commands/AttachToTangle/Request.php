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

namespace Techworker\IOTA\RemoteApi\Commands\AttachToTangle;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Cryptography\POW\PowInterface;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Request
 *
 * This method attaches the specified transactions to the Tangle by doing Proof
 * of Work together with the trunk- and branch-transaction and the given weight
 * magnitude.
 *
 * If the provided node blocks requests to attachToTangle (Node::doesPOW), the
 * POW will be performed locally by one of the PowInterface implementations.
 *
 * @link https://iota.readme.io/docs/attachtotangle
 */
class Request extends AbstractRequest
{
    /**
     * Trunk transaction to approve.
     *
     * @var TransactionHash
     */
    protected $trunkTransactionHash;

    /**
     * Branch transaction to approve.
     *
     * @var TransactionHash
     */
    protected $branchTransactionHash;

    /**
     * Proof of Work intensity
     *
     * @var int
     */
    protected $minWeightMagnitude;

    /**
     * List of trytes (raw transaction data) to attach to the tangle.
     *
     * @var Transaction[]
     */
    protected $transactions;

    /**
     * POW implementation.
     *
     * @var PowInterface
     */
    protected $pow;

    /**
     * Little state helper.
     *
     * @var TransactionHash
     */
    protected $previousTxHash;

    /**
     * The factory to create a new curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * Request constructor.
     *
     * @param PowInterface $pow
     * @param HttpClientInterface $httpClient
     * @param CurlFactory $curlFactory
     * @param Node $node
     */
    public function __construct(PowInterface $pow, HttpClientInterface $httpClient, CurlFactory $curlFactory, Node $node)
    {
        $this->pow = $pow;
        $this->curlFactory = $curlFactory;
        parent::__construct($httpClient, $node);
    }

    /**
     * Sets the trunk transaction hash.
     *
     * @param TransactionHash $trunkTransactionHash
     *
     * @return Request
     */
    public function setTrunkTransactionHash(TransactionHash $trunkTransactionHash): self
    {
        $this->trunkTransactionHash = $trunkTransactionHash;

        return $this;
    }

    /**
     * Gets the trunk tansaction hash.
     *
     * @return TransactionHash
     */
    public function getTrunkTransactionHash(): TransactionHash
    {
        return $this->trunkTransactionHash;
    }

    /**
     * Sets the branch transaction hash.
     *
     * @param TransactionHash $branchTransactionHash
     *
     * @return Request
     */
    public function setBranchTransactionHash(TransactionHash $branchTransactionHash): self
    {
        $this->branchTransactionHash = $branchTransactionHash;

        return $this;
    }

    /**
     * Gets the branch transaction hash.
     *
     * @return TransactionHash
     */
    public function getBranchTransactionHash(): TransactionHash
    {
        return $this->branchTransactionHash;
    }

    /**
     * Sets the min weight magnitude.
     *
     * @param int $minWeightMagnitude
     *
     * @return Request
     */
    public function setMinWeightMagnitude(int $minWeightMagnitude): self
    {
        $this->minWeightMagnitude = $minWeightMagnitude;

        return $this;
    }

    /**
     * Gets the min weight magnitude.
     *
     * @return int
     */
    public function getMinWeightMagnitude(): int
    {
        return $this->minWeightMagnitude;
    }

    /**
     * Overwrites all transactions.
     *
     * @param Transaction[] $transactions
     *
     * @return Request
     */
    public function setTransactions(array $transactions): self
    {
        $this->transactions = [];
        foreach ($transactions as $t) {
            $this->addTransaction($t);
        }

        return $this;
    }

    /**
     * Adds a single transaction instance.
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
     * Gets the list of all transactions.
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
            'command' => 'attachToTangle',
            'trunkTransaction' => (string) $this->trunkTransactionHash,
            'branchTransaction' => (string) $this->branchTransactionHash,
            'minWeightMagnitude' => $this->minWeightMagnitude,
            'trytes' => array_map('\strval', $this->transactions),
        ];
    }

    /**
     * Executes the request.
     *
     * @return AbstractResponse|Response
     * @throws Exception
     * @throws \Techworker\IOTA\Exception
     * @throws \InvalidArgumentException
     */
    public function execute(): Response
    {
        $response = new Response($this->curlFactory, $this);

        // node does pow?
        if ($this->node->doesPOW()) {
            $srvResponse = $this->httpClient->commandRequest($this);
            $response->initialize($srvResponse['code'], $srvResponse['raw']);
        } else {
            // local pow
            $response->initialize(200, json_encode([
                'trytes' => array_map('\strval', $this->loopTransactions()),
            ]));
        }

        return $response->finish()->throwOnError();
    }

    /**
     * Loops all transactions and does the pow and adjusts the nonce in each
     * transaction.
     *
     * @return array
     * @throws \Techworker\IOTA\Exception
     * @throws \InvalidArgumentException
     */
    protected function loopTransactions(): array
    {
        $this->previousTxHash = null;

        $finalBundleTrytes = [];
        foreach ($this->transactions as $trytes) {
            $finalBundleTrytes[] = $this->getBundleTrytes($trytes);
        }

        return array_reverse($finalBundleTrytes);
    }

    /**
     * Executes the pow for the given transaction.
     *
     * @param Transaction $transaction
     *
     * @return Transaction
     *
     * @throws \Techworker\IOTA\Exception
     * @throws \InvalidArgumentException
     */
    protected function getBundleTrytes(Transaction $transaction): Transaction
    {
        $transaction->setAttachmentTimestamp(time() * 1000);
        $transaction->setAttachmentTimestampLowerBound(0);
        $transaction->setAttachmentTimestampUpperBound((3 ** 27 - 1) / 2);

        // If this is the first transaction to be processed make sure that it's
        // the last in the bundle and then assign it the supplied trunk and
        // branch transactions
        if (null === $this->previousTxHash) {
            // Check if last transaction in the bundle
            if ($transaction->getLastIndex() !== $transaction->getCurrentIndex()) {
                throw new \Techworker\IOTA\Exception(
                    'Wrong bundle order. The bundle should be ordered in descending order from currentIndex'
                );
            }

            $transaction->setTrunkTransactionHash($this->trunkTransactionHash);
            $transaction->setBranchTransactionHash($this->branchTransactionHash);
        } else {
            // Chain the bundle together via the trunkTransaction (previous tx in the bundle)
            // Assign the supplied trunkTransaction as branchTransaction
            $transaction->setTrunkTransactionHash($this->previousTxHash);
            $transaction->setBranchTransactionHash($this->trunkTransactionHash);
        }

        $tx = $this->pow->execute($this->minWeightMagnitude, $transaction);
        $transaction = new Transaction($this->curlFactory, $tx);

        // Assign the previousTxHash to this tx
        $this->previousTxHash = $transaction->getTransactionHash();

        return $transaction;
    }

    /**
     * Gets the array representation of the request.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'trunkTransactionHash' => $this->trunkTransactionHash->serialize(),
            'branchTransactionHash' => (string) $this->branchTransactionHash->serialize(),
            'minWeightMagnitude' => $this->minWeightMagnitude,
            'transactions' => SerializeUtil::serializeArray($this->transactions)
        ];
    }
}
