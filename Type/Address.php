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

/**
 * Class Address.
 *
 * 81-trytes long address. In addition it might contain a checksum.
 */
class Address extends Trytes implements CheckSummableInterface
{
    use CheckSummableTrait;

    /**
     * The index position of the address. -1 if not defined.
     *
     * @var int
     */
    protected $index = -1;

    /**
     * Address constructor.
     *
     * @param null|string $address
     * @param int         $index
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $address = null, int $index = -1)
    {
        if (null !== $address) {
            $length = \strlen($address);
            if (81 !== $length && 90 !== $length) {
                throw new \InvalidArgumentException(sprintf(
                    'An address must be 81/90 (+checksum) trytes long: %s',
                    $address
                ));
            }

            // extract the checksum and save separately
            if (90 === $length) {
                $checkSum = substr($address, 81, 9);
                /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                $address = substr($address, 0, 81);
                $this->checkSum = new Trytes($checkSum);
            }
        }

        $this->index = $index;
        parent::__construct($address);
    }

    /**
     * Gets the address as string including the checksum if available.
     *
     * @return string
     */
    public function __toString(): string
    {
        if (null === $this->checkSum) {
            return parent::__toString();
        }

        return parent::__toString().(string) $this->checkSum;
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
     * Gets the array version of the object.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'checkSum' => $this->hasChecksum() ? (string) $this->checkSum : null,
            'index' => $this->index,
        ]);
    }
}
