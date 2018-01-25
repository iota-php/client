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

namespace IOTA\Cryptography;

use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;
use IOTA\Util\TritsUtil;

/**
 * Class MultiSig.
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
     * @param Seed          $seed
     * @param int           $index
     * @param SecurityLevel $security
     *
     * @return \IOTA\Type\Trytes
     */
    public function getDigest(Seed $seed, int $index, SecurityLevel $security)
    {
        $key = Signing::key($this->kerlFactory, $seed, $index, $security);
        $digests = Signing::digests($this->kerlFactory, $key);

        return TritsUtil::toTrytes($digests);
    }
}
