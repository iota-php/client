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

namespace Techworker\IOTA\Util;

use Techworker\IOTA\Cryptography\Hashing\KerlFactory;
use Techworker\IOTA\Cryptography\Signing;
use Techworker\IOTA\Exception;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\CheckSummableInterface;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Type\Trytes;

/**
 * Class ChecksumUtil.
 *
 * Utility functions related to checksums.
 */
class CheckSumUtil
{
    /**
     * The kerl factory.
     *
     * @var KerlFactory
     */
    protected $kerlFactory;

    /**
     * CheckSumUtil constructor.
     *
     * @param KerlFactory $kerlFactory
     */
    public function __construct(KerlFactory $kerlFactory)
    {
        $this->kerlFactory = $kerlFactory;
    }

    /**
     * Clones the given instance, adds the checksum and returns the cloned
     * instance.
     *
     * @param CheckSummableInterface $trytes
     * @param int $length
     *
     * @return Trytes
     *
     * @throws Exception
     */
    public function getChecksum(CheckSummableInterface $trytes, int $length = 9): Trytes
    {
        if(!$trytes instanceof Trytes) {
            throw new Exception('Checksum can only be calculated on a Trytes instance.');
        }

        /** @var Trytes $trytes */
        $kerl = $this->kerlFactory->factory();
        $kerl->reset();
        $instanceTrits = TrytesUtil::toTrits($trytes);
        $checksumTrits = [];

        $kerl->absorb($instanceTrits, 0, \count($instanceTrits));
        $kerl->squeeze($checksumTrits, 0, $kerl->hashLength());

        // last $checkSumLength trytes (27 trits) as checksum
        return TritsUtil::toTrytes(\array_slice(
            $checksumTrits, 243 - ($length * 3), $length * 3)
        );
    }
}
