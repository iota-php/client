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

use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Util\TryteUtil;

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
     * @param string|null $trytes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $trytes = null)
    {
        if (null === $trytes) {
            return;
        }

        $this->trytes = $trytes;
    }

    /**
     * Gets the current tryte array.
     *
     * @return string[]|\ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        // split up into chars and add them as a new Tryte.
        $chars = str_split($this->trytes);

        return new \ArrayIterator($chars);
    }

    /**
     * Gets all trits from all trytes.
     *
     * @return array
     */
    public function toTrits(): array
    {
        $trits = [];

        foreach ($this->getIterator() as $tryte) {
            foreach (TryteUtil::toTrits($tryte) as $trit) {
                $trits[] = $trit;
            }
        }

        return $trits;
    }

    /**
     * Returns the trytes.
     *
     * @return string
     */
    public function __toString(): string
    {
        /** @noinspection MagicMethodsValidityInspection */
        return $this->trytes;
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

    public function equals(self $trytes): bool
    {
        return $this->trytes === $trytes->trytes;
    }

    public function serialize()
    {
        return [
            'trytes' => $this->trytes
        ];
    }
}
