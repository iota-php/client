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

namespace Techworker\IOTA\Type;

/**
 * Class Seed.
 *
 * A seed to access an account. A seed cannot be dumped or serialized - you'll
 * have to call getValue() explicitly to get the seed value.
 *
 * Shootout to the https://github.com/paragonie/halite and their HiddenString
 * class.
 */
class Seed extends Trytes
{
    /**
     * Creates a new Seed instance.
     *
     * @param string $seed
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $seed = null)
    {
        if (null !== $seed && \strlen($seed) < 81) {
            throw new \InvalidArgumentException('Seed length is 81');
        }
        parent::__construct(substr($seed, 0, 81));
    }

    /**
     * Hide its internal state from var_dump().
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'seed' => '*',
            'attention' => 'A seed value cannot be dumped in any form. Call getSeed explicitly to get the seed value.',
        ];
    }

    /**
     * Gets the seed as a string.
     *
     * @return string
     */
    public function getSeed(): string
    {
        return parent::__toString();
    }

    /**
     * Will always return an empty string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return '';
    }

    /**
     * Cannot be serialized in any way.
     *
     * @return array
     */
    public function __sleep(): array
    {
        return [];
    }
}
