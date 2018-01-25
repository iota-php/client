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

namespace Techworker\IOTA\Type;

use Techworker\IOTA\SerializeInterface;

/**
 * Class Trytes.
 *
 * This class represents a collection of trytes.
 */
class Trytes implements \IteratorAggregate, \Countable, SerializeInterface
{
    /**
     * The trytes string.
     *
     * @var string
     */
    protected $trytes = '';

    /**
     * Trytes constructor.
     *
     * @param null|string $trytes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $trytes = null)
    {
        if (null === $trytes) {
            return;
        }

        if (0 !== preg_match('/[^A-Z9]/', $trytes)) {
            throw new \InvalidArgumentException('Invalid trytes.');
        }

        $this->trytes = $trytes;
    }

    /**
     * Returns the trytes.
     *
     * @return string
     */
    public function __toString(): string
    {
        // @noinspection MagicMethodsValidityInspection
        return $this->trytes;
    }

    /**
     * Gets the current tryte array.
     *
     * @return \ArrayIterator|string[]
     */
    public function getIterator(): \ArrayIterator
    {
        // split up into chars and add them as a new Tryte.
        $chars = str_split($this->trytes);

        return new \ArrayIterator($chars);
    }

    /**
     * @todo: should be length
     *
     * @return int
     */
    public function count(): int
    {
        return \strlen($this->trytes);
    }

    /**
     * Gets a value indicating whether the tryte equals other trytes.
     *
     * @param Trytes $trytes
     *
     * @return bool
     */
    public function equals(self $trytes): bool
    {
        return $this->trytes === $trytes->trytes;
    }

    /**
     * Gets the serialized version of the trytes.
     *
     * @return array
     */
    public function serialize()
    {
        return [
            'trytes' => $this->trytes,
        ];
    }
}
