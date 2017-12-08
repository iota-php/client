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

namespace Techworker\IOTA\Cryptography;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Type\Bundle;
use Techworker\IOTA\Type\HMACKey;
use Techworker\IOTA\Type\SignatureMessageFragment;
use Techworker\IOTA\Util\TritsUtil;
use Techworker\IOTA\Util\TrytesUtil;

class HMAC
{
    /**
     * The key.
     *
     * @var HMACKey
     */
    protected $key;

    /**
     * The factory to initialize the hashing method.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * The rounds for the hashing function.
     *
     * @var int
     */
    protected $rounds;

    /**
     * HMAC constructor.
     *
     * @param int      $rounds
     * @param HMACKey  $key
     * @param CurlFactory $curlFactory
     */
    public function __construct(int $rounds, HMACKey $key, CurlFactory $curlFactory)
    {
        $this->curlFactory = $curlFactory;
        $this->rounds = $rounds;
        $this->key = $key;
    }

    /**
     * Adds the HMAC key to the signature of all bundles in the transaction.
     *
     * @param Bundle $bundle
     */
    public function addHMAC(Bundle $bundle): void
    {
        $curl = $this->curlFactory->factory($this->rounds);
        foreach ($bundle->getTransactions() as $transaction) {
            if (!$transaction->getValue()->isPos()) {
                continue;
            }

            $bundleHashTrits = TrytesUtil::toTrits($bundle->getBundleHash());
            $hmac = [];
            $curl->initialize();
            $curl->absorb(TrytesUtil::toTrits($this->key), 0, $curl->hashLength());
            $curl->absorb($bundleHashTrits, 0, $curl->hashLength());
            $curl->squeeze($hmac, 0, $curl->hashLength());
            $hmacTrytes = TritsUtil::toTrytes($hmac);

            // set first 81 trytes of the message to the hmac result
            $transaction->setSignatureMessageFragment(
                new SignatureMessageFragment(
                    (string) $hmacTrytes.
                    substr((string) $transaction->getSignatureMessageFragment(), 81, 2187)
                )
            );
        }
    }
}
