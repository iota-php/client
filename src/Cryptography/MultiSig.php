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

use Techworker\IOTA\Cryptography\Hashing\KerlFactory;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\TritsUtil;

/**
 * Class MultiSig
 * @package Techworker\IOTA\Cryptography
 */
class MultiSig
{
    /**
     * Factory to get a new kerl instance.
     *
     * @var KerlFactory
     */
    protected $kerlFactory;

    /**
     * MultiSig constructor.
     *
     * @param KerlFactory $kerlFactory
     */
    public function __construct(KerlFactory $kerlFactory)
    {
        $this->kerlFactory = $kerlFactory;
    }

    /**
     * @param Seed $seed
     * @param int $index
     * @param SecurityLevel $security
     * @return \Techworker\IOTA\Type\Trytes
     */
    public function getDigest(Seed $seed, int $index, SecurityLevel $security) {

        $key = Signing::key($this->kerlFactory, $seed, $index, $security);
        $digests = Signing::digests($this->kerlFactory, $key);
        return TritsUtil::toTrytes($digests);
    }
}