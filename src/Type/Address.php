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
 * Class Address.
 *
 * 81-trytes long address. In addition it might contain a checksum.
 */
class Address extends Trytes implements SerializeInterface
{
    /**
     * The checksum of the address (9 Tryte).
     *
     * @var Trytes
     */
    protected $checksum;

    /**
     * The index position of the address. -1 if not defined.
     *
     * @var int
     */
    protected $index = -1;

    /**
     * Address constructor.
     *
     * @param string|null $address
     * @param int $index
     * @throws \InvalidArgumentException
     */
    public function __construct(string $address = null, int $index = -1)
    {
        if (null !== $address) {
            $length = \strlen($address);
            if (81 !== $length && 90 !== $length) {
                throw new \InvalidArgumentException(sprintf(
                    'An address must be 81/90 chars long: %s',
                    $address
                ));
            }

            // extract the checksum and save separately
            if (90 === $length) {
                $checksum = substr($address, 81, 9);
                /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                $address = substr($address, 0, 81);
                $this->checksum = new Trytes($checksum);
            }
        }

        $this->index = $index;
        parent::__construct($address);
    }

    /**
     * Sets the checksum of the address.
     *
     * @param Trytes $checksum
     *
     * @return Address
     */
    public function setChecksum(Trytes $checksum): self
    {
        $this->checksum = $checksum;

        return $this;
    }

    /**
     * Gets the index of the address.
     *
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * Gets a value indicating whether the address has an index.
     *
     * @return bool
     */
    public function hasIndex(): bool
    {
        return $this->index !== -1;
    }

    /**
     * Gets the address as string including the checksum if available.
     *
     * @return string
     */
    public function __toString(): string
    {
        if (null === $this->checksum) {
            return parent::__toString();
        }

        return parent::__toString().(string) $this->checksum;
    }

    /**
     * Gets a value indicating whether the address has a checksum.
     *
     * @return bool
     */
    public function hasChecksum(): bool
    {
        return null !== $this->checksum;
    }

    /**
     * Removes the checksum from the address.
     */
    public function removeChecksum()
    {
        $this->checksum = null;
    }

    /**
     * Gets the array version of the object.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'trytes' => $this->trytes,
            'checksum' => $this->hasChecksum() ? $this->checksum : null,
            'index' => $this->index
        ];
    }
}
