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

namespace Techworker\IOTA\Cryptography\Hashing;

use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Trytes;
use Techworker\IOTA\Util\TritsUtil;
use Techworker\IOTA\Util\TrytesUtil;

/**
 * Class MultiSigAddress.
 */
class MultiSigAddress
{
    /**
     * Kerl instance.
     *
     * @var Kerl
     */
    protected $kerl;

    /**
     * MultiSigAddress constructor.
     *
     * @param KerlFactory $kerlFactory
     */
    public function __construct(KerlFactory $kerlFactory)
    {
        $this->kerl = $kerlFactory->factory();
    }

    /**
     * @param Trytes[] $addressDigests
     */
    public function absorb(array $addressDigests): void
    {
        foreach ($addressDigests as $digest) {
            $this->kerl->absorb(TrytesUtil::toTrits($digest), 0);
        }
    }

    /**
     * Gets a new multi sign address.
     *
     * @return Address
     */
    public function finalize(): Address
    {
        // Squeeze the address trits
        $addressTrits = [];
        $this->kerl->squeeze($addressTrits, 0, Curl::HASH_LENGTH);

        // TODO: totrytes -> tostring -> toAddress()? Same in Signing::address()
        return new Address((string) TritsUtil::toTrytes($addressTrits));
    }
}
