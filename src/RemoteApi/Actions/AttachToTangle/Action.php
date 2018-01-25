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
use IOTA\Cryptography\POW\PowInterface;
use IOTA\Node;
use IOTA\RemoteApi\AbstractAction;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\RemoteApi\NodeApiClient;
use IOTA\Type\Transaction;
use IOTA\Type\TransactionHash;
use IOTA\Util\SerializeUtil;

/**
 * Class Request.
 *
 * This method attaches the specified transactions to the Tangle by doing Proof
 * of Work together with the trunk- and branch-transaction and the given weight
 * magnitude.
 *
 * If the provided node blocks requests to attachToTangle (Node::doesPOW), the
 * POW will be performed locally by one of the PowInterface implementations.
 *
 * @see https://iota.readme.io/docs/attachtotangle
 */
class Action extends AbstractAction
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
     * Proof of Work intensity.
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
     * @var TransactionHash|null
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
     * @param PowInterface        $pow
     * @param NodeApiClient $httpClient
     * @param CurlFactory         $curlFactory
     * @param Node                $node
     */
    public function __construct(PowInterface $pow, NodeApiClient $httpClient, CurlFactory $curlFactory, Node $node)
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
     * @return Action
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
     * @return Action
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
     * @return Action
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
     * @return Action
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
     * @throws Exception
     * @throws \IOTA\Exception
     * @throws \InvalidArgumentException
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this->curlFactory, $this);

        // node does pow?
        if ($this->node->doesPOW()) {
            $srvResponse = $this->nodeApiClient->send($this);
            $result->initialize($srvResponse['code'], $srvResponse['raw']);
        } else {
            // local pow
            $result->initialize(200, json_encode([
                'trytes' => array_map('\strval', $this->loopTransactions()),
            ]));
        }

        return $result->finish()->throwOnError();
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
            'branchTransactionHash' => $this->branchTransactionHash->serialize(),
            'minWeightMagnitude' => $this->minWeightMagnitude,
            'transactions' => SerializeUtil::serializeArray($this->transactions),
        ];
    }

    /**
     * Loops all transactions and does the pow and adjusts the nonce in each
     * transaction.
     *
     * @throws \IOTA\Exception
     * @throws \InvalidArgumentException
     *
     * @return array
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
     * @throws \IOTA\Exception
     * @throws \InvalidArgumentException
     *
     * @return Transaction
     */
    protected function getBundleTrytes(Transaction $transaction): Transaction
    {
        $transaction->setAttachmentTimestamp(time() * 1000);
        $transaction->setAttachmentTimestampLowerBound(0);
        $transaction->setAttachmentTimestampUpperBound((int)((3 ** 27 - 1) / 2));

        // If this is the first transaction to be processed make sure that it's
        // the last in the bundle and then assign it the supplied trunk and
        // branch transactions
        if (null === $this->previousTxHash) {
            // Check if last transaction in the bundle
            if ($transaction->getLastIndex() !== $transaction->getCurrentIndex()) {
                throw new \IOTA\Exception(
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
}
