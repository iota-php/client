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

namespace IOTA\Type;

/**
 * Class Tag.
 *
 * A tag.
 */
class Tag extends Trytes
{
    /**
     * Tag constructor.
     *
     * @param null|string $trytes
     */
    public function __construct(string $trytes = null)
    {
        if (null !== $trytes) {
            $trytes = \str_pad($trytes, 27, '9');
        }
        parent::__construct($trytes);
    }
}
