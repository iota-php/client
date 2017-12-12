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

namespace Techworker\IOTA\Type;

use Techworker\IOTA\Cryptography\Adder;
use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Cryptography\Hashing\KerlFactory;
use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Util\TritsUtil;
use Techworker\IOTA\Util\TrytesUtil;

/**
 * Class Bundle.
 */
class Bundle implements SerializeInterface
{
    /**
     * The hash of the bundle.
     *
     * @var BundleHash
     */
    protected $bundleHash;

    /**
     * The list of transactions in the bundle.
     *
     * @var Transaction[]
     */
    protected $transactions = [];

    /**
     * The factory to create a new Kerl instance.
     *
     * @var KerlFactory
     */
    protected $kerlFactory;

    /**
     * The factory to create a new Curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * Bundle constructor.
     * @param KerlFactory $kerlFactory
     * @param CurlFactory $curlFactory
     * @param BundleHash|null $hash
     */
    public function __construct(KerlFactory $kerlFactory, CurlFactory $curlFactory, BundleHash $hash = null)
    {
        $this->kerlFactory = $kerlFactory;
        $this->curlFactory = $curlFactory;
        $this->bundleHash = $hash;
    }

    /**
     * Adds a transaction hash to the list of transaction hashes.
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
     * Gets all transactions.
     *
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Gets the hash of the bundle.
     *
     * @return BundleHash
     */
    public function getBundleHash(): ?BundleHash
    {
        return $this->bundleHash;
    }

    /**
     * Sets the hash of the bundle.
     *
     * @param BundleHash $bundleHash
     *
     * @return Bundle
     */
    public function setBundleHash(BundleHash $bundleHash): self
    {
        $this->bundleHash = $bundleHash;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * Adds a transaction by creating a new transaction instance with the given
     * parameters.
     *
     * @param int $signatureMessageLength
     * @param Address $address
     * @param Iota $value
     * @param Tag $tag
     * @param int $timestamp
     */
    public function addNewTransaction(
        int $signatureMessageLength,
                                      Address $address,
                                      Iota $value,
                                      Tag $tag,
                                      int $timestamp
    ) {
        for ($i = 0; $i < $signatureMessageLength; ++$i) {
            $transaction = Transaction::createTransaction(
                $this->curlFactory,
                $address,
                0 === $i ? $value : Iota::ZERO(),
                $tag,
                $timestamp
            );
            $this->addTransaction($transaction);
        }
    }

    /**
     * Finalizes the bundle.
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function finalize()
    {
        $validBundle = false;
        while (!$validBundle) {
            $kerl = $this->kerlFactory->factory();
            $kerl->initialize();

            // loop all transactions
            foreach ($this->transactions as $i => $transaction) {
                $valueTrits = TritsUtil::fromInt($transaction->getValue()->getAmount(), 81);
                $timestampTrits = TritsUtil::fromInt((string) $transaction->getTimestamp(), 27);
                if (null === $transaction->getCurrentIndex()) {
                    $transaction->setCurrentIndex($i);
                }
                $currentIndexTrits = TritsUtil::fromInt((string) $transaction->getCurrentIndex(), 27);
                $transaction->setLastIndex(\count($this->getTransactions()) - 1);
                $lastIndexTrits = TritsUtil::fromInt((string) $transaction->getLastIndex(), 27);

                $bundleEssence = TrytesUtil::toTrits(new Trytes(
                    (string) $transaction->getAddress().
                    (string) TritsUtil::toTrytes($valueTrits).
                    (string) $transaction->getObsoleteTag().
                    (string) TritsUtil::toTrytes($timestampTrits).
                    (string) TritsUtil::toTrytes($currentIndexTrits).
                    (string) TritsUtil::toTrytes($lastIndexTrits)
                ));
                $kerl->absorb($bundleEssence, 0, \count($bundleEssence));
            }

            $hash = [];
            $kerl->squeeze($hash, 0, $kerl->hashLength());
            $this->bundleHash = new BundleHash((string)TritsUtil::toTrytes($hash));

            foreach ($this->transactions as $transaction) {
                $transaction->setBundleHash($this->bundleHash);
            }

            $normalized = $this->bundleHash->normalized();
            if (\in_array(13, $normalized, true)) {
                // insecure bundle, increment tag and recompute bundle hash.
                $increasedTag = Adder::add(
                    TrytesUtil::toTrits($this->transactions[0]->getObsoleteTag()),
                    [1]
                );
                $this->transactions[0]->setObsoleteTag(new Tag((string) TritsUtil::toTrytes($increasedTag)));
            } else {
                $validBundle = true;
            }
        }
    }

    /**
     * Adds the given signature message fragments to the transactions.
     *
     * @param SignatureMessageFragment[] $signatureFragments
     * @throws \InvalidArgumentException
     */
    public function addSignatureMessageFragments(array $signatureFragments)
    {
        $emptySignatureFragment = new SignatureMessageFragment(str_repeat('9', 2187));
        $emptyHash = new TransactionHash(str_repeat('9', 81));
        $emptyTag = new Tag(str_repeat('9', 27));
        $emptyTimestamp = TritsUtil::toInt(TrytesUtil::toTrits(new Trytes(str_repeat('9', 9))));

        foreach ($this->getTransactions() as $i => $transaction) {
            // Fill empty signatureMessageFragment
            $transaction->setSignatureMessageFragment(
                $signatureFragments[$i] ?? $emptySignatureFragment
            );

            $transaction->setTrunkTransactionHash($emptyHash);
            $transaction->setBranchTransactionHash($emptyHash);
            $transaction->setAttachmentTimestamp((int) $emptyTimestamp);
            $transaction->setAttachmentTimestampLowerBound((int) $emptyTimestamp);
            $transaction->setAttachmentTimestampUpperBound((int) $emptyTimestamp);
            $transaction->setNonce($emptyTag);
        }
    }

    /**
     * Gets the array version of the object.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'hash' => $this->bundleHash->serialize(),
            'transactions' => array_map(function (Transaction $transaction) {
                return $transaction->serialize();
            }, $this->transactions),
        ];
    }
}
