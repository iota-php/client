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

/**
 * Class Iota.
 *
 * A value object to handle iota units and calculation with big numbers.
 */
class Iota implements SerializeInterface
{
    public const UNIT_IOTA = '1';
    public const UNIT_KILO = '1000';
    public const UNIT_MEGA = '1000000';
    public const UNIT_GIGA = '1000000000';
    public const UNIT_TERA = '1000000000000';
    public const UNIT_PETA = '1000000000000000';

    public const IOTA_MAX = '2779530283277761';

    /**
     * The amount of iota (smallest unit).
     *
     * @var string
     */
    private $amount;

    /**
     * Iota constructor.
     *
     * @param string|int $amount
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($amount)
    {
        if (\bccomp(self::IOTA_MAX, (string) $amount) < 0) {
            throw new \InvalidArgumentException(
                'Impossible iota amount given. The maximum supply is '.self::IOTA_MAX
            );
        }

        $this->amount = (string) $amount;
    }

    /**
     * Gets the iota amount as a string.
     *use Techworker\IOTA\Base\Cryptography\Kerl;.
     *
     * @return string
     */
    public function getAmount(): string
    {
        return $this->__toString();
    }

    /**
     * Adds the given amount to the current amount.
     *
     * @param Iota $iota
     *
     * @return Iota
     * @throws \InvalidArgumentException
     */
    public function plus(self $iota): self
    {
        return new self(\bcadd($this->amount, $iota->getAmount()));
    }

    /**
     * Subtracts the given iota amount from the current amount.
     *
     * @param Iota $iota
     *
     * @return Iota
     * @throws \InvalidArgumentException
     */
    public function minus(self $iota): self
    {
        return new self(\bcsub($this->amount, $iota->getAmount()));
    }

    /**
     * Multiplies the current iota amount with the given amount.
     *
     * @param int|string $multiplier
     *
     * @return Iota
     * @throws \InvalidArgumentException
     */
    public function multiplyBy($multiplier): self
    {
        return new self(\bcmul($this->amount, (string) $multiplier));
    }

    /**
     * Divides the current iota value and returns a new one.
     *
     * @param int|string $divisor
     *
     * @return Iota
     * @throws \InvalidArgumentException
     */
    public function divideBy($divisor): self
    {
        return new self(\bcdiv($this->amount, (string) $divisor));
    }

    /**
     * Gets the Kilo Iota amount.
     *
     * @return string
     */
    public function getKiloIota(): string
    {
        return \bcdiv($this->amount, self::UNIT_KILO, 15);
    }

    /**
     * Gets the Mega Iota amount.
     *
     * @return string
     */
    public function getMegaIota(): string
    {
        return \bcdiv($this->amount, self::UNIT_MEGA, 15);
    }

    /**
     * Gets the Giga Iota amount.
     *
     * @return string
     */
    public function getGigaIota(): string
    {
        return \bcdiv($this->amount, self::UNIT_GIGA, 15);
    }

    /**
     * Gets the Tera Iota amount.
     *
     * @return string
     */
    public function getTeraIota(): string
    {
        return \bcdiv($this->amount, self::UNIT_TERA, 15);
    }

    /**
     * Gets the Peta Iota amount.
     *
     * @return string
     */
    public function getPetaIota(): string
    {
        return \bcdiv($this->amount, self::UNIT_PETA, 15);
    }

    /**
     * @param string $petaIota
     *
     * @return Iota
     *
     * @throws \InvalidArgumentException
     */
    public static function fromPetaIota(string $petaIota): self
    {
        return new self(\bcmul($petaIota, self::UNIT_PETA));
    }

    /**
     * @param string $teraIota
     *
     * @return Iota
     *
     * @throws \InvalidArgumentException
     */
    public static function fromTeraIota(string $teraIota): self
    {
        return new self(\bcmul($teraIota, self::UNIT_TERA));
    }

    /**
     * @param string $gigaIota
     *
     * @return Iota
     *
     * @throws \InvalidArgumentException
     */
    public static function fromGigaIota(string $gigaIota): self
    {
        return new self(\bcmul($gigaIota, self::UNIT_GIGA));
    }

    /**
     * @param string $megaIota
     *
     * @return Iota
     *
     * @throws \InvalidArgumentException
     */
    public static function fromMegaIota(string $megaIota): self
    {
        return new self(\bcmul($megaIota, self::UNIT_MEGA));
    }

    /**
     * @param string $kiloIota
     *
     * @return Iota
     *
     * @throws \InvalidArgumentException
     */
    public static function fromKiloIota(string $kiloIota): self
    {
        return new self(\bcmul($kiloIota, self::UNIT_KILO));
    }

    /**
     * Gets a value indicating whether the given Iota value is lower than
     * the current value.
     *
     * @param Iota $value
     *
     * @return bool
     */
    public function lt(self $value): bool
    {
        return \bccomp($this->getAmount(), $value->getAmount()) < 0;
    }

    /**
     * Gets a value indicating whether the given Iota value is greater than
     * the current value.
     *
     * @param Iota $value
     *
     * @return bool
     */
    public function gt(self $value): bool
    {
        return \bccomp($this->getAmount(), $value->getAmount()) > 0;
    }

    /**
     * Gets a value indicating whether the given Iota value is greater than or
     * equal the current value.
     *
     * @param Iota $value
     *
     * @return bool
     */
    public function gteq(self $value): bool
    {
        return \bccomp($this->getAmount(), $value->getAmount()) >= 0;
    }

    /**
     * Gets a value indicating whether the given Iota value is lower than or
     * equal the current value.
     *
     * @param Iota $value
     *
     * @return bool
     */
    public function lteq(self $value): bool
    {
        return \bccomp($this->getAmount(), $value->getAmount()) <= 0;
    }

    /**
     * Gets a value indicating whether the given Iota value equals the current
     * value.
     *
     * @param Iota $value
     *
     * @return bool
     */
    public function eq(self $value): bool
    {
        return 0 === \bccomp($this->getAmount(), $value->getAmount());
    }

    /**
     * Gets a value indicating whether the given Iota value does not equal the
     * current value.
     *
     * @param Iota $value
     *
     * @return bool
     */
    public function neq(self $value): bool
    {
        return 0 !== \bccomp($this->getAmount(), $value->getAmount());
    }

    /**
     * toString implementation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->amount;
    }

    /**
     * Gets a 0 Iota instance.
     *
     * @return Iota
     * @throws \InvalidArgumentException
     */
    public static function ZERO(): self
    {
        return new self(0);
    }

    /**
     * Gets a value indicating whether the value is negative.
     *
     * @return bool
     */
    public function isNeg(): bool
    {
        return $this->lt(self::ZERO());
    }

    /**
     * Gets a value indicating whether the value is positive.
     *
     * @return bool
     */
    public function isPos(): bool
    {
        return $this->gt(self::ZERO());
    }

    /**
     * Gets a value indicating whether the value equals 0.
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->eq(self::ZERO());
    }

    /**
     * Gets the string representation.
     *
     * @return string
     */
    public function serialize() : string
    {
        return $this->__toString();
    }
}
