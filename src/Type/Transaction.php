<?php

namespace Techworker\IOTA\Type;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Util\TritsUtil;
use Techworker\IOTA\Util\TrytesUtil;

/**
 * Class Transaction.
 *
 * A class representing the details of a transaction.
 */
class Transaction extends Trytes
{
    /**
     * The transaction hash.
     *
     * @var TransactionHash
     */
    protected $transactionHash;

    /**
     * The signature message fragment.
     *
     * @var SignatureMessageFragment
     */
    protected $signatureMessageFragment;

    /**
     * The address to send the iota to.
     *
     * @var Address
     */
    protected $address;

    /**
     * The value of the transaction.
     *
     * @var Iota
     */
    protected $value;

    /**
     * The tag of the transaction (obsolete).
     *
     * @var Tag
     */
    protected $obsoleteTag;

    /**
     * The tag of the transaction.
     *
     * @var Tag
     */
    protected $tag;

    /**
     * The timestamp when the transaction took place.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * The attachment timestamp.
     *
     * @var int
     */
    protected $attachmentTimestamp;

    /**
     * The lower bound attachment timestamp.
     *
     * @var int
     */
    protected $attachmentTimestampLowerBound;

    /**
     * The upper bound attachment timestamp.
     *
     * @var int
     */
    protected $attachmentTimestampUpperBound;

    /**
     * The current index in the bundle.
     *
     * @var int
     */
    protected $currentIndex;

    /**
     * The last index in the bundle.
     *
     * @var int
     */
    protected $lastIndex;

    /**
     * The bundle of the transaction.
     *
     * @var BundleHash
     */
    protected $bundleHash;

    /**
     * The trunk transaction.
     *
     * @var TransactionHash
     */
    protected $trunkTransactionHash;

    /**
     * The branch transaction.
     *
     * @var TransactionHash
     */
    protected $branchTransactionHash;

    /**
     * Nonce.
     *
     * @var Trytes
     */
    protected $nonce;

    /**
     * A value indicating whether the transaction is persisted.
     *
     * @var bool
     */
    protected $persistence;

    /**
     * @var CurlFactory
     */
    protected $curlFactory;

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * Creates a new transaction with the given values.
     *
     * @param CurlFactory $curlFactory
     * @param Address $address
     * @param Iota $value
     * @param Tag $tag
     * @param int $timestamp
     * @return Transaction
     */
    public static function createTransaction(CurlFactory $curlFactory, Address $address, Iota $value, Tag $tag, int $timestamp) : Transaction
    {
        $instance = new self($curlFactory);
        $instance->address = $address;
        $instance->value = $value;
        $instance->obsoleteTag = $tag;
        $instance->tag = $tag;
        $instance->timestamp = $timestamp;

        return $instance;
    }

    /**
     * Creates a new Transaction from the given trytes string.
     *
     * @param CurlFactory $curlFactory
     * @param null|string $trytes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(CurlFactory $curlFactory, string $trytes = null)
    {
        parent::__construct($trytes);
        $this->curlFactory = $curlFactory;
        if (null === $trytes) {
            return;
        }

        // validity check
        for ($i = 2279; $i < 2295; ++$i) {
            if ('9' !== $this->trytes[$i]) {
                throw new \InvalidArgumentException(
                    'The transaction trytes need the value 9 between pos >=2279 and <2295'
                );
            }
        }

        $this->parse();
    }

    /**
     * Tries to parse the current trytes string.
     */
    protected function parse() : void
    {
        $trits = TrytesUtil::toTrits($this);
        $curl = $this->curlFactory->factory();
        $curl->initialize();
        $curl->absorb($trits, 0, \count($trits));

        $hash = [];
        $curl->squeeze($hash, 0, 243);

        $this->transactionHash = new TransactionHash(TritsUtil::toTrytes($hash));
        $this->signatureMessageFragment = new SignatureMessageFragment(
            substr($this->trytes, 0, 2187)
        );

        // Info: I kept the numbers from the iota.js lib so its simpler to
        // compare any changes.
        $this->address = new Address(substr($this->trytes, 2187, 2268 - 2187));
        $this->value = new Iota(TritsUtil::toInt(\array_slice($trits, 6804, 6837 - 6804)));
        $this->obsoleteTag = new Tag(substr($this->trytes, 2295, 2322 - 2295));
        $this->timestamp = gmp_intval(TritsUtil::toInt(\array_slice($trits, 6966, 6993 - 6966)));
        $this->currentIndex = gmp_intval(TritsUtil::toInt(\array_slice($trits, 6993, 7020 - 6993)));
        $this->lastIndex = gmp_intval(TritsUtil::toInt(\array_slice($trits, 7020, 7047 - 7020)));

        $this->bundleHash = new BundleHash(
            substr($this->trytes, 2349, 2430 - 2349)
        );

        $this->trunkTransactionHash = new TransactionHash(
            substr($this->trytes, 2430, 2511 - 2430)
        );

        $this->branchTransactionHash = new TransactionHash(
            substr($this->trytes, 2511, 2592 - 2511)
        );

        $this->tag = new Tag(
            substr($this->trytes, 2592, 2619 - 2592)
        );
        $this->attachmentTimestamp = gmp_intval(TritsUtil::toInt(\array_slice($trits, 7857, 7884 - 7857)));
        $this->attachmentTimestampLowerBound = gmp_intval(TritsUtil::toInt(\array_slice($trits, 7884, 7911 - 7884)));
        $this->attachmentTimestampUpperBound = gmp_intval(TritsUtil::toInt(\array_slice($trits, 7911, 7938 - 7911)));

        $this->nonce = new Trytes(
            substr($this->trytes, 2646, 2673 - 2646)
        );
    }

    /**
     * Will get the original string representation of a transaction.
     *
     * @return string
     */
    public function __toString(): string
    {
        // TODO: is the padding even necessary?
        $valueTrits = TritsUtil::fromInt($this->value->getAmount(), 81);
        while (\count($valueTrits) < 81) {
            $valueTrits[] = 0;
        }

        $timestampTrits = TritsUtil::fromInt($this->timestamp, 27);
        while (\count($timestampTrits) < 27) {
            $timestampTrits[] = 0;
        }

        $currentIndexTrits = TritsUtil::fromInt($this->currentIndex, 27);
        while (\count($currentIndexTrits) < 27) {
            $currentIndexTrits[] = 0;
        }

        $lastIndexTrits = TritsUtil::fromInt($this->lastIndex, 27);
        while (\count($lastIndexTrits) < 27) {
            $lastIndexTrits[] = 0;
        }

        $attachmentTimestampTrits = TritsUtil::fromInt($this->attachmentTimestamp, 27);
        while (\count($attachmentTimestampTrits) < 27) {
            $attachmentTimestampTrits[] = 0;
        }

        $attachmentTimestampLowerBoundTrits = TritsUtil::fromInt($this->attachmentTimestampLowerBound, 27);
        while (\count($attachmentTimestampLowerBoundTrits) < 27) {
            $attachmentTimestampLowerBoundTrits[] = 0;
        }

        $attachmentTimestampUpperBoundTrits = TritsUtil::fromInt($this->attachmentTimestampUpperBound, 27);
        while (\count($attachmentTimestampUpperBoundTrits) < 27) {
            $attachmentTimestampUpperBoundTrits[] = 0;
        }

        $this->setTag($this->getTag() ?? $this->obsoleteTag);

        return $this->signatureMessageFragment.
            (string) $this->address.
            (string) TritsUtil::toTrytes($valueTrits).
            (string) $this->obsoleteTag.
            (string) TritsUtil::toTrytes($timestampTrits).
            (string) TritsUtil::toTrytes($currentIndexTrits).
            (string) TritsUtil::toTrytes($lastIndexTrits).
            (string) $this->bundleHash.
            (string) $this->trunkTransactionHash.
            (string) $this->branchTransactionHash.
            (string) $this->tag.
            (string) TritsUtil::toTrytes($attachmentTimestampTrits).
            (string) TritsUtil::toTrytes($attachmentTimestampLowerBoundTrits).
            (string) TritsUtil::toTrytes($attachmentTimestampUpperBoundTrits).
            (string) $this->nonce;
    }

    /**
     * Gets the hash of the transaction.
     *
     * @return TransactionHash
     */
    public function getTransactionHash(): TransactionHash
    {
        return $this->transactionHash;
    }

    /**
     * Gets the signature message fragment.
     *
     * @return SignatureMessageFragment
     */
    public function getSignatureMessageFragment(): SignatureMessageFragment
    {
        return $this->signatureMessageFragment;
    }

    /**
     * Gets the address of the transaction.
     *
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * Gets the value (amount) of the transaction.
     *
     * @return Iota
     */
    public function getValue(): Iota
    {
        return $this->value;
    }

    /**
     * Gets the tag of the transaction (obsolete).
     *
     * @return Tag
     */
    public function getObsoleteTag(): ?Tag
    {
        return $this->obsoleteTag;
    }

    /**
     * Sets the obsolete tag.
     *
     * @param Tag $obsoleteTag
     *
     * @return Transaction
     */
    public function setObsoleteTag(Tag $obsoleteTag): self
    {
        $this->obsoleteTag = $obsoleteTag;

        return $this;
    }

    /**
     * Gets the timestamp of the transaction.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Gets the current index in the bundle.
     *
     * @return int
     */
    public function getCurrentIndex(): ?int
    {
        return $this->currentIndex;
    }

    /**
     * Sets the current index in the bundle.
     *
     * @param int $index
     *
     * @return Transaction
     */
    public function setCurrentIndex(int $index): self
    {
        $this->currentIndex = $index;

        return $this;
    }

    /**
     * Gets the last index in the bundle.
     *
     * @return int
     */
    public function getLastIndex(): int
    {
        return $this->lastIndex;
    }

    /**
     * Gets the bundle of the transaction.
     *
     * @return BundleHash
     */
    public function getBundleHash(): BundleHash
    {
        return $this->bundleHash;
    }

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
     * Gets the nonce.
     *
     * @return Trytes
     */
    public function getNonce(): Trytes
    {
        return $this->nonce;
    }

    /**
     * Gets a value indicating whether the transaction was persisted.
     *
     * @return bool
     */
    public function getPersistence(): bool
    {
        return $this->persistence;
    }

    /**
     * Sets a value indicating whether the transaction was persisted.
     *
     * @param bool $persistence
     *
     * @return Transaction
     */
    public function setPersistence(bool $persistence): self
    {
        $this->persistence = $persistence;

        return $this;
    }

    /**
     * Sets the last index.
     *
     * @param int $lastIndex
     *
     * @return $this
     */
    public function setLastIndex(int $lastIndex)
    {
        $this->lastIndex = $lastIndex;

        return $this;
    }

    /**
     * Sets the corresponding bundle hash.
     *
     * @param BundleHash $bundleHash
     * @return $this
     */
    public function setBundleHash(BundleHash $bundleHash) : Transaction
    {
        $this->bundleHash = $bundleHash;

        return $this;
    }

    /**
     * Gets the tag.
     *
     * @return Tag
     */
    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    /**
     * Gets the attachment timestamp.
     *
     * @return int
     */
    public function getAttachmentTimestamp(): int
    {
        return $this->attachmentTimestamp;
    }

    /**
     * Gets the attachment timestamp lowerbound.
     *
     * @return int
     */
    public function getAttachmentTimestampLowerBound(): int
    {
        return $this->attachmentTimestampLowerBound;
    }

    /**
     * Gets the attachment timestamp upperbound.
     *
     * @return int
     */
    public function getAttachmentTimestampUpperBound(): int
    {
        return $this->attachmentTimestampUpperBound;
    }

    /**
     * Sets the signature message fragment.
     *
     * @param SignatureMessageFragment $signatureMessageFragment
     *
     * @return Transaction
     */
    public function setSignatureMessageFragment(SignatureMessageFragment $signatureMessageFragment): self
    {
        $this->signatureMessageFragment = $signatureMessageFragment;

        return $this;
    }

    /**
     * Sets the tag.
     *
     * @param Tag $tag
     *
     * @return Transaction
     */
    public function setTag(Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Sets the attachment timestamp.
     *
     * @param int $attachmentTimestamp
     *
     * @return Transaction
     */
    public function setAttachmentTimestamp(int $attachmentTimestamp): self
    {
        $this->attachmentTimestamp = $attachmentTimestamp;

        return $this;
    }

    /**
     * Sets the attachment timestamp lower bound.
     * @param int $attachmentTimestampLowerBound
     *
     * @return Transaction
     */
    public function setAttachmentTimestampLowerBound(int $attachmentTimestampLowerBound): self
    {
        $this->attachmentTimestampLowerBound = $attachmentTimestampLowerBound;

        return $this;
    }

    /**
     * Sets the attachment timestamp upper bound.
     *
     * @param int $attachmentTimestampUpperBound
     *
     * @return Transaction
     */
    public function setAttachmentTimestampUpperBound(int $attachmentTimestampUpperBound): self
    {
        $this->attachmentTimestampUpperBound = $attachmentTimestampUpperBound;

        return $this;
    }

    /**
     * Sets the trunk transaction hash.
     *
     * @param TransactionHash $trunkTransactionHash
     *
     * @return Transaction
     */
    public function setTrunkTransactionHash(TransactionHash $trunkTransactionHash): self
    {
        $this->trunkTransactionHash = $trunkTransactionHash;

        return $this;
    }

    /**
     * Sets the branch transaction hash.
     *
     * @param TransactionHash $branchTransactionHash
     *
     * @return Transaction
     */
    public function setBranchTransactionHash(TransactionHash $branchTransactionHash): self
    {
        $this->branchTransactionHash = $branchTransactionHash;

        return $this;
    }

    /**
     * Sets the nonce.
     *
     * @param Trytes $nonce
     *
     * @return Transaction
     */
    public function setNonce(Trytes $nonce): self
    {
        $this->nonce = $nonce;

        return $this;
    }

    /**
     * Gets the serialized version of the transaction.
     *
     * @return array
     */
    public function serialize() : array
    {
        return [
            'hash' => $this->transactionHash->serialize(),
            'signatureMessageFragment' => $this->signatureMessageFragment->serialize(),
            'address' => $this->address->serialize(),
            'value' => $this->value->serialize(),
            'obsoleteTag' => $this->obsoleteTag->serialize(),
            'tag' => $this->tag->serialize(),
            'timestamp' => $this->timestamp,
            'attachmentTimestamp' => $this->attachmentTimestamp,
            'attachmentTimestampLowerBound' => $this->attachmentTimestampLowerBound,
            'attachmentTimestampUpperBound' => $this->attachmentTimestampUpperBound,
            'currentIndex' => $this->currentIndex,
            'lastIndex' => $this->lastIndex,
            'bundle' => $this->bundleHash->serialize(),
            'trunkTransaction' => $this->trunkTransactionHash->serialize(),
            'branchTransaction' => $this->branchTransactionHash->serialize(),
            'nonce' => $this->nonce->serialize(),
            'persistence' => $this->persistence,
        ];
    }
}
