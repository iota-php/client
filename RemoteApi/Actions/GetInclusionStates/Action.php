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

namespace Techworker\IOTA\RemoteApi\Actions\GetInclusionStates;

use Techworker\IOTA\RemoteApi\AbstractAction;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Get the inclusion states of a set of transactions. This is for determining
 * if a transaction was accepted and confirmed by the network or not. You can
 * search for multiple tips (and thus, milestones) to get past inclusion states
 * of transactions.
 *
 * This API call simply returns a list of boolean values in the same order as
 * the transaction list you submitted, thus you get a true/false whether a
 * transaction is confirmed or not.
 *
 * @see https://iota.readme.io/docs/getinclusionstates
 */
class Action extends AbstractAction
{
    /**
     * List of transaction hashes you want to get the inclusion state for.
     *
     * @var TransactionHash[]
     */
    protected $transactionHashes;

    /**
     * List of tips (including milestones) you want to search for the inclusion
     * state.
     *
     * @var Tip[]
     */
    protected $tips;

    /**
     * Sets the transactions hashes.
     *
     * @param TransactionHash[] $transactionHashes
     *
     * @return Action
     */
    public function setTransactionHashes(array $transactionHashes): self
    {
        $this->transactionHashes = [];
        foreach ($transactionHashes as $transactionHash) {
            $this->addTransactionHash($transactionHash);
        }

        return $this;
    }

    /**
     * Adds a single transaction hash.
     *
     * @param TransactionHash $transactionHash
     *
     * @return $this
     */
    public function addTransactionHash(TransactionHash $transactionHash)
    {
        $this->transactionHashes[] = $transactionHash;

        return $this;
    }

    /**
     * Gets a list of the transaction hashes.
     *
     * @return TransactionHash[]
     */
    public function getTransactionHashes(): array
    {
        return $this->transactionHashes;
    }

    /**
     * Sets the tips.
     *
     * @param Tip[] $tips
     *
     * @return Action
     */
    public function setTips(array $tips): self
    {
        $this->tips = [];
        foreach ($tips as $tip) {
            $this->addTip($tip);
        }

        return $this;
    }

    /**
     * Adds a single tip.
     *
     * @param Tip $tip
     *
     * @return Action
     */
    public function addTip(Tip $tip): self
    {
        $this->tips[] = $tip;

        return $this;
    }

    /**
     * Gets the list of tips.
     *
     * @return Tip[]
     */
    public function getTips(): array
    {
        return $this->tips;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'getInclusionStates',
            'transactions' => array_map('\strval', $this->transactionHashes),
            'tips' => array_map('\strval', $this->tips),
        ];
    }

    /**
     * Executes the request.
     *
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $response = new Result($this);
        $srvResponse = $this->nodeApiClient->send($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactions' => SerializeUtil::serializeArray($this->transactionHashes),
            'tips' => SerializeUtil::serializeArray($this->tips),
        ]);
    }
}
