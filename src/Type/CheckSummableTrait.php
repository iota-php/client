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

use Techworker\IOTA\Exception;
use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Util\TryteUtil;

/**
 * Class CheckSummableInterface.
 *
 * Class that implement this interface can be used to generate a checksum.
 */
trait CheckSummableTrait
{
    /**
     * The checksum of the address (9 Tryte).
     *
     * @var Trytes
     */
    protected $checkSum;

    /**
     * @inheritdoc
     */
    public function setCheckSum(Trytes $checkSum): CheckSummableInterface
    {
        /** @var CheckSummableInterface $clone */
        $clone = clone $this;
        $clone->checkSum = $checkSum;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function hasChecksum(): bool
    {
        return null !== $this->checkSum;
    }

    /**
     * @inheritdoc
     */
    public function removeChecksum() : CheckSummableInterface
    {
        /** @var CheckSummableInterface $clone */
        $clone = clone $this;
        $clone->checkSum = null;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getCheckSum(): ?Trytes
    {
        return $this->checkSum;
    }
}
