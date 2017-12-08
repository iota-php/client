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

namespace Techworker\IOTA\Cryptography\Keccak384;

/**
 * Interface Keccak384Interface.
 *
 * An interface that defines the keccak384 functionality to easily test
 * different implementations.
 */
interface Keccak384Interface
{
    /**
     * Takes the given hashes as an array of byte arrays and returns the hex
     * presentation of the keccak384 result.
     *
     * @param array $hashes
     *
     * @return string
     */
    public function digest(array $hashes): string;
}
