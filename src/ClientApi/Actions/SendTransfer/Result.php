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

namespace Techworker\IOTA\ClientApi\Actions\SendTransfer;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\Bundle;
use Techworker\IOTA\Type\TransactionHash;

class Result extends AbstractResult
{
    /**
     * The bundle.
     *
     * @var Bundle|array
     */
    protected $bundle = [];

    /**
     * @var TransactionHash
     */
    protected $trunkTransactionHash;

    /**
     * @var TransactionHash
     */
    protected $branchTransactionHash;

    /**
     * Gets the resulting bundle.
     *
     * @return Bundle
     */
    public function getBundle(): Bundle
    {
        return $this->bundle;
    }

    /**
     * Sets the bundle.
     *
     * @param Bundle $bundle
     *
     * @return Result
     */
    public function setBundle(Bundle $bundle): self
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * @return TransactionHash
     */
    public function getTrunkTransactionHash(): TransactionHash
    {
        return $this->trunkTransactionHash;
    }

    /**
     * @param TransactionHash $trunkTransactionHash
     *
     * @return Result
     */
    public function setTrunkTransactionHash(TransactionHash $trunkTransactionHash): self
    {
        $this->trunkTransactionHash = $trunkTransactionHash;

        return $this;
    }

    /**
     * @return TransactionHash
     */
    public function getBranchTransactionHash(): TransactionHash
    {
        return $this->branchTransactionHash;
    }

    /**
     * @param TransactionHash $branchTransactionHash
     *
     * @return Result
     */
    public function setBranchTransactionHash(TransactionHash $branchTransactionHash): self
    {
        $this->branchTransactionHash = $branchTransactionHash;

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
            'bundle' => $this->bundle->serialize(),
            'trunkTransactionHash' => $this->trunkTransactionHash->serialize(),
            'branchTransactionHash' => $this->branchTransactionHash->serialize(),
        ], parent::serialize());
    }
}
