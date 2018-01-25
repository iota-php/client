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

namespace IOTA\RemoteApi\Actions\IsTailConsistent;

use IOTA\RemoteApi\AbstractAction;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\Type\TransactionHash;

/**
 * Class Action.
 *
 * TODO
 */
class Action extends AbstractAction
{
    /**
     * The tail transaction hash.
     *
     * @var TransactionHash
     */
    protected $tailTransactionHash;

    /**
     * Sets the transaction hash.
     *
     * @param TransactionHash $tailTransactionHash
     *
     * @return Action
     */
    public function setTailTransactionHash(TransactionHash $tailTransactionHash): self
    {
        $this->tailTransactionHash = $tailTransactionHash;

        return $this;
    }

    /**
     * Gets the list of transaction hashes.
     *
     * @return TransactionHash
     *
     * @todo allow null?
     */
    public function getTailTransactionHash(): TransactionHash
    {
        return $this->tailTransactionHash;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'isTailConsistent',
            'tail' => (string) $this->tailTransactionHash,
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
            'tailTransactionHash' => $this->tailTransactionHash->serialize(),
        ]);
    }
}
