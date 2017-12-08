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

namespace Techworker\IOTA\ClientApi\Actions\GetLatestInclusion;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\TransactionHash;

class Result extends AbstractResult
{
    /**
     * The list of inclusion states by transaction hash.
     *
     * @var bool[]
     */
    protected $states = [];

    /**
     * Gets the list of addresses.
     *
     * @return Address[]
     */
    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * Adds a state for a transaction.
     *
     * @param TransactionHash $transactionHash
     * @param bool            $state
     *
     * @return Result
     */
    public function addState(TransactionHash $transactionHash, bool $state): self
    {
        $this->states[(string) $transactionHash] = $state;

        return $this;
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'states' => $this->states,
        ], parent::serialize());
    }
}
