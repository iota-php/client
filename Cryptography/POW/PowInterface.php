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

namespace Techworker\IOTA\Cryptography\POW;

use Techworker\IOTA\Type\Transaction;

/**
 * Interface PowInterface.
 *
 * Interface for pow implementations.
 */
interface PowInterface
{
    /**
     * Executes the pow for the given transaction.
     *
     * @param int         $minWeightMagnitude
     * @param Transaction $trytes
     *
     * @return string
     */
    public function execute(int $minWeightMagnitude, Transaction $trytes): string;
}
