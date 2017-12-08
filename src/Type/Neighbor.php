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
 * Class Neighbor.
 *
 * Neighbor information.
 *
 * @see https://iota.readme.io/docs/getneighborsactivity
 */
class Neighbor implements SerializeInterface
{
    /**
     * The address of the peer.
     *
     * @var string
     */
    protected $address;

    /**
     * Number of all transactions sent (invalid, valid, already-seen).
     *
     * @var int
     */
    protected $numberOfAllTransactions;

    /**
     * Invalid transactions your peer has sent you. These are transactions with
     * invalid signatures or overall schema.
     *
     * @var int
     */
    protected $numberOfInvalidTransactions;

    /**
     * New transactions which were transmitted.
     *
     * @var int
     */
    protected $numberOfNewTransactions;

    /**
     * Neighbor constructor.
     *
     * @param string $address
     * @param int    $numberOfAllTransactions
     * @param int    $numberOfInvalidTransactions
     * @param int    $numberOfNewTransactions
     */
    public function __construct(
        $address,
                                $numberOfAllTransactions,
                                $numberOfInvalidTransactions,
                                $numberOfNewTransactions
    ) {
        $this->address = $address;
        $this->numberOfAllTransactions = $numberOfAllTransactions;
        $this->numberOfInvalidTransactions = $numberOfInvalidTransactions;
        $this->numberOfNewTransactions = $numberOfNewTransactions;
    }

    /**
     * Gets the address of your peer.
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Gets the number of all transactions sent (invalid, valid, already-seen).
     *
     * @return int
     */
    public function getNumberOfAllTransactions(): int
    {
        return $this->numberOfAllTransactions;
    }

    /**
     * Gets the invalid transactions your peer has sent you. These are
     * transactions with invalid signatures or overall schema.
     *
     * @return int
     */
    public function getNumberOfInvalidTransactions(): int
    {
        return $this->numberOfInvalidTransactions;
    }

    /**
     * Gets the new transactions which were transmitted.
     *
     * @return int
     */
    public function getNumberOfNewTransactions(): int
    {
        return $this->numberOfNewTransactions;
    }

    public function serialize()
    {
        return [
            // TODO: !!
        ];
    }
}
