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

namespace IOTA\Cryptography\Hashing;

/**
 * Class CurlFactory.
 *
 * Creates a new curl instance.
 */
class CurlFactory
{
    /**
     * Creates a new curl instance and returns it.
     *
     * @param int $rounds
     *
     * @return Curl
     */
    public function factory(int $rounds = 81): Curl
    {
        return new Curl($rounds);
    }
}
