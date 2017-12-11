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

use Techworker\IOTA\Cryptography\Keccak384\Keccak384Interface;
use Techworker\IOTA\Util\TritsUtil;

/**
 * Class Kerl.
 */
class Kerl implements SpongeInterface
{
    /**
     * The hash length.
     */
    public const HASH_LENGTH = 243;

    /**
     * An array that holds all generated hashes as byte arrays.
     *
     * @var array
     */
    protected $hashes = [];

    /**
     * The keccak384 implementation.
     *
     * @var Keccak384Interface
     */
    protected $keccak;

    public function __construct(Keccak384Interface $keccak)
    {
        $this->keccak = $keccak;
    }

    /**
     * @param int[]|null $state
     *
     * @throws \Exception
     */
    public function initialize(array $state = null): void
    {
    }

    /**
     * Resets the list of hashes.
     */
    public function reset(): void
    {
        $this->hashes = [];
    }

    /**
     * Sponge absorb function.
     *
     * @param int[]    $trits
     * @param int      $offset
     * @param int|null $length
     */
    public function absorb(array $trits, int $offset, int $length = null): void
    {
        if (null === $length) {
            $length = \count($trits);
        }

        if (0 !== $length % self::HASH_LENGTH) {
            throw new \InvalidArgumentException('Illegal length provided');
        }

        while ($offset < $length) {
            $stop = min($offset + self::HASH_LENGTH, $length);

            if (self::HASH_LENGTH === $stop - $offset) {
                $trits[$stop - 1] = 0;
            }

            $t = \array_slice($trits, $offset, self::HASH_LENGTH);
            $signedNums = TritsUtil::toBytes($t);

            $unsignedBytes = [];
            foreach ($signedNums as $byte) {
                $unsignedBytes[] = $this->convertSign($byte);
            }

            $this->hashes[] = $unsignedBytes;
            $offset += self::HASH_LENGTH;
        }
    }

    /**
     * Sponge squueze function.
     *
     * @param array $trits
     * @param int   $offset
     * @param int   $length
     */
    public function squeeze(array &$trits, int $offset, int $length): void
    {
        if (0 !== $length % self::HASH_LENGTH) {
            throw new \InvalidArgumentException('Illegal length provided');
        }

        while ($offset < $length) {
            // unpack from digest response
            $unsignedHash = array_values(unpack('C*', hex2bin($this->digest())));

            $signedHash = [];
            foreach ($unsignedHash as $ush) {
                $signedHash[] = $this->convertSign($ush);
            }

            $tritsFromHash = TritsUtil::fromBytes($signedHash, self::HASH_LENGTH);
            $tritsFromHash[self::HASH_LENGTH - 1] = 0;

            $stop = min(self::HASH_LENGTH, $length - $offset);
            array_splice($trits, $offset, $offset + $stop, \array_slice($tritsFromHash, 0, $stop));

            $flippedBytes = array_map(function ($b) {
                return $this->convertSign(~$b);
            }, $unsignedHash);

            $this->reset();
            $this->hashes = [$flippedBytes];
            $offset += self::HASH_LENGTH;
        }
    }

    /**
     * This method returns the digest of the collected hashes. There is no
     * official keccak-384 implementation, so we are using a small python script
     * for now.
     *
     * @return string
     */
    protected function digest() : string
    {
        return $this->keccak->digest($this->hashes);
    }

    /**
     * Converts the sign of the given byte value.
     *
     * @param int|string $byte
     *
     * @return int
     */
    protected function convertSign($byte) : ?int
    {
        if ($byte < 0) {
            return 256 + $byte;
        } elseif ($byte > 127) {
            return -256 + $byte;
        } else {
            return $byte;
        }
    }

    public function hashLength(): int
    {
        return self::HASH_LENGTH;
    }
}
