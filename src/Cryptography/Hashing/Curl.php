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

namespace Techworker\IOTA\Cryptography\Hashing;

use Techworker\IOTA\Exception;

/**
 * Class Curl.
 */
class Curl implements SpongeInterface
{
    /**
     * @var array
     */
    protected const TRUTH_TABLE = [1, 0, -1, 2, 1, -1, 0, 2, -1, 1, 0];

    /**
     * The length of the hash.
     */
    public const HASH_LENGTH = 243;

    /**
     * The current state.
     *
     * @var int[]
     */
    protected $state;

    /**
     * The number of rounds to do in the transformation.
     *
     * @var int
     */
    protected $rounds;

    /**
     * Curl constructor.
     *
     * @param int $rounds
     */
    public function __construct(int $rounds = 81)
    {
        $this->rounds = $rounds;
    }

    /**
     * Initializes the state with 729 trits if no initial state is provided.
     *
     * @param int[] $state
     */
    public function initialize(array $state = null): void
    {
        $this->state = array_fill(0, 729, 0);
        if (null !== $state) {
            $this->state = $state;
        }
    }

    /**
     * Sponge absorb function.
     *
     * @param int[] $trits
     * @param int   $offset
     * @param int   $length
     */
    public function absorb(array $trits, int $offset, int $length): void
    {
        do {
            $i = 0;
            $limit = ($length < 243 ? $length : 243);

            while ($i < $limit) {
                $this->state[$i++] = $trits[$offset++];
            }

            $this->transform();
        } while (($length -= 243) > 0);
    }

    /**
     * @param array $trits
     * @param int $offset
     * @param int $length
     */
    public function squeeze(array &$trits, int $offset, int $length) : void
    {
        do {
            $i = 0;
            $limit = ($length < 243 ? $length : 243);

            while ($i < $limit) {
                $trits[$offset++] = $this->state[$i++];
            }

            $this->transform();
        } while (($length -= 243) > 0);
    }

    /**
     * Sponge transform function.
     */
    protected function transform()
    {
        $index = 0;
        for ($round = 0; $round < $this->rounds; ++$round) {
            $stateCopy = $this->state;
            for ($i = 0; $i < 729; ++$i) {
                // todo: unreadable shit
                $this->state[$i] = self::TRUTH_TABLE[
                    $stateCopy[$index] + ($stateCopy[
                        $index += ($index < 365 ? 364 : -365)
                    ] << 2) + 5];
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function reset(): void
    {
        throw new Exception('Not implemented');
    }

    public function hashLength(): int
    {
        return self::HASH_LENGTH;
    }
}
