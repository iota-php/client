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

use Techworker\IOTA\Cryptography\Keccak384\Keccak384Interface;

/**
 * Class KerlFactory.
 *
 * Creates a new Kerl instance.
 */
class KerlFactory
{
    /**
     * A keccak 384 implementation instance.
     *
     * @var Keccak384Interface
     */
    protected $keccak384;

    /**
     * KerlFactory constructor.
     *
     * @param Keccak384Interface $keccak384
     */
    public function __construct(Keccak384Interface $keccak384)
    {
        $this->keccak384 = $keccak384;
    }

    /**
     * Creates a new Kerl instance and returns it.
     *
     * @return Kerl
     */
    public function factory(): Kerl
    {
        return new Kerl($this->keccak384);
    }
}
