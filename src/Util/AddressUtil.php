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

namespace IOTA\Util;

use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Cryptography\Signing;
use IOTA\Type\Address;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;
use IOTA\Type\Trytes;

/**
 * Class AddressUtil.
 *
 * Utility functions related to addresses.
 */
class AddressUtil
{
    /**
     * The kerl factory.
     *
     * @var KerlFactory
     */
    protected $kerlFactory;

    /**
     * Utility to calculate checksums.
     *
     * @var CheckSumUtil
     */
    protected $checkSumUtil;

    /**
     * AddressUtil constructor.
     *
     * @param KerlFactory  $kerlFactory
     * @param CheckSumUtil $checkSumUtil
     */
    public function __construct(KerlFactory $kerlFactory, CheckSumUtil $checkSumUtil)
    {
        $this->kerlFactory = $kerlFactory;
        $this->checkSumUtil = $checkSumUtil;
    }

    /**
     * Calculates and returns the checksum of the given address.
     *
     * @param Address $address
     *
     * @return Trytes
     */
    public function getChecksum(Address $address): Trytes
    {
        $kerl = $this->kerlFactory->factory();
        $kerl->reset();
        $addressTrits = TrytesUtil::toTrits($address);
        $checksumTrits = [];

        $kerl->absorb($addressTrits, 0, \count($addressTrits));
        $kerl->squeeze($checksumTrits, 0, $kerl->hashLength());

        // last 9 trytes (27 trits) as checksum
        return TritsUtil::toTrytes(\array_slice($checksumTrits, 243 - 27, 27));
    }

    /**
     * Returns a new Address instance for the given data.
     *
     * @param Seed          $seed
     * @param int           $index
     * @param SecurityLevel $security
     * @param bool          $addChecksum
     *
     * @throws \InvalidArgumentException
     *
     * @return Address
     */
    public function generateAddress(
        Seed $seed,
        int $index,
        SecurityLevel $security,
        bool $addChecksum
    ): Address {
        $key = Signing::key($this->kerlFactory, $seed, $index, $security);
        $digests = Signing::digests($this->kerlFactory, $key);
        $address = Signing::address($this->kerlFactory, $digests, $index);

        if ($addChecksum) {
            $address = $address->setCheckSum(
                $this->checkSumUtil->getChecksum($address)
            );
        }

        return $address;
    }
}
