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
Interface CheckSummableInterface
{
    /**
     * Removes the checksum.
     *
     * @return CheckSummableInterface
     */
    public function removeCheckSum() : CheckSummableInterface;

    /**
     * Gets the current checksum.
     *
     * @return Trytes
     */
    public function getCheckSum() : ?Trytes;

    /**
     * Adds the given checksum.
     *
     * @param Trytes $checkSum
     * @return CheckSummableInterface
     */
    public function setCheckSum(Trytes $checkSum) : CheckSummableInterface;

    /**
     * Gets a value indicating whether the instance has a checksum.
     *
     * @return bool
     */
    public function hasChecksum() : bool;
}