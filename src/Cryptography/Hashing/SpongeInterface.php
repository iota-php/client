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

/**
 * Interface SpongeInterface.
 *
 * Sponge interface.
 */
interface SpongeInterface
{
    /**
     * Initializes the state.
     *
     * @param null|array $state
     */
    public function initialize(array $state = null): void;

    /**
     * @param array $trits
     * @param int   $offset
     * @param int   $length
     */
    public function absorb(array $trits, int $offset, int $length): void;

    /**
     * @param array $trits
     * @param int   $offset
     * @param int   $length
     *
     * @return mixed
     */
    public function squeeze(array &$trits, int $offset, int $length);

    /**
     * Resets the results.
     */
    public function reset(): void;

    /**
     * Gets the hash length.
     *
     * @return int
     */
    public function hashLength(): int;
}
