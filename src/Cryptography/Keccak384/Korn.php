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

namespace IOTA\Cryptography\Keccak384;

use kornrunner\Keccak;

/**
 * Class Korn.
 *
 * Uses the keccak implementation from https://github.com/kornrunner/php-keccak
 */
class Korn implements Keccak384Interface
{
    /**
     * {@inheritdoc}
     */
    public function digest(array $hashes): string
    {
        $s = '';
        foreach ($hashes as $hash) {
            $s .= implode('', array_map('\chr', $hash));
        }

        return Keccak::hash($s, 384);
    }
}
