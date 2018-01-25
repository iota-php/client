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

namespace IOTA\ClientApi\Actions\GetBundle;

use IOTA\ClientApi\AbstractAction;
use IOTA\ClientApi\AbstractResult;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Exception;
use IOTA\Node;
use IOTA\RemoteApi\Actions\GetTrytes;
use IOTA\Type\Bundle;
use IOTA\Type\BundleHash;
use IOTA\Type\Transaction;
use IOTA\Type\TransactionHash;

/**
 * Replays a transfer by doing Proof of Work again.
 */
class Action extends AbstractAction
{
    use GetTrytes\ActionTrait;

    /**
     * @var TransactionHash
     */
    protected $transactionHash;

    /**
     * The factory to create a new kerl instance.
     *
     * @var KerlFactory
     */
    protected $kerlFactory;

    /**
     * The factory to create a new curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * The result instance.
     *
     * @var Result
     */
    protected $result;

    /**
     * Action constructor.
     *
     * @param Node                     $node
     * @param GetTrytes\ActionFactory $getTrytesFactory
     * @param KerlFactory              $kerlFactory
     * @param CurlFactory              $curlFactory
     */
    public function __construct(Node $node, GetTrytes\ActionFactory $getTrytesFactory, KerlFactory $kerlFactory, CurlFactory $curlFactory)
    {
        $this->kerlFactory = $kerlFactory;
        $this->curlFactory = $curlFactory;
        $this->setGetTrytesFactory($getTrytesFactory);
        parent::__construct($node);
    }

    /**
     * Sets the transaction hash.
     *
     * @param TransactionHash $transactionHash
     *
     * @return Action
     */
    public function setTransactionHash(TransactionHash $transactionHash): self
    {
        $this->transactionHash = $transactionHash;

        return $this;
    }

    /**
     * Executes the action.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $this->result = new Result($this);
        $this->result->setBundle($this->traverseBundle($this->transactionHash));

        return $this->result->finish();
    }

    /**
     * Basically traverse the Bundle by going down the trunkTransactions until
     * the bundle hash of the transaction is no longer the same. In case the input
     * transaction hash is not a tail, we return an error.
     *
     * @param TransactionHash $trunkTx
     * @param null|BundleHash $bundleHash
     * @param Bundle          $bundle
     *
     * @throws \Exception
     *
     * @return Bundle
     */
    public function traverseBundle(
        TransactionHash $trunkTx,
        BundleHash $bundleHash = null,
        Bundle $bundle = null
    ): Bundle {
        if (null === $bundle) {
            $bundle = new Bundle($this->kerlFactory, $this->curlFactory, $bundleHash);
        }

        // Get trytes of transaction hash
        $getTrytesResponse = $this->getTrytes($this->node, [$trunkTx]);
        $this->result->addChildTrace($getTrytesResponse->getTrace());

        if (0 === \count($getTrytesResponse->getTransactions())) {
            // TODO: what?
            throw new Exception('Bundle transactions not visible');
        }

        $transaction = new Transaction(
            $this->curlFactory,
            (string) $getTrytesResponse->getTransactions()[0]
        );

        if (null === $bundleHash && 0 !== $transaction->getCurrentIndex()) {
            throw new Exception('Invalid tail transaction supplied.');
        }

        // If no bundle hash, define it
        if (null === $bundleHash) {
            $bundleHash = $transaction->getBundleHash();
            $bundle->setBundleHash($bundleHash);
        }

        // If different bundle hash, return with bundle
        if ((string) $bundleHash !== (string) $transaction->getBundleHash()) {
            return $bundle;
        }

        // If only one bundle element, return
        if (0 === $transaction->getLastIndex() && 0 === $transaction->getCurrentIndex()) {
            return $bundle->addTransaction($transaction);
        }

        // Define new trunkTransaction for search
        $trunkTx = $transaction->getTrunkTransactionHash();

        // Add transaction object to bundle
        $bundle->addTransaction($transaction);

        // Continue traversing with new trunkTx
        return $this->traverseBundle($trunkTx, $bundleHash, $bundle);
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactionHash' => $this->transactionHash->serialize(),
        ]);
    }
}
