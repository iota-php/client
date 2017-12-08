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

namespace Techworker\IOTA\ClientApi\Actions;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\ClientApi;
use Techworker\IOTA\RemoteApi\RemoteApi;
use Techworker\IOTA\Type\Bundle;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\TransactionHash;

/**
 * Replays a transfer by doing Proof of Work again.
 */
class ReplayBundle extends AbstractAction
{
    /**
     * Tail transaction.
     *
     * @var TransactionHash[]
     */
    protected $tail;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @var int
     */
    protected $minWeightMagnitude;

    /**
     * ReplayBundle constructor.
     *
     * @param RemoteApi       $remoteApi
     * @param ClientApi       $clientApi
     * @param TransactionHash $tail
     * @param int             $depth
     * @param int             $minWeightMagnitude
     */
    public function __construct(
        RemoteApi $remoteApi,
        ClientApi $clientApi,
        TransactionHash $tail,
        int $depth,
        int $minWeightMagnitude
    ) {
        parent::__construct();
        $this->tail = $tail;
        $this->depth = $depth;
        $this->minWeightMagnitude = $minWeightMagnitude;
    }

    /**
     * Executes the action.
     *
     * @return Transaction[]
     */
    public function execute(): array
    {
        /** @var Bundle $bundle */
        $bundle = $this->clientApi->getBundle($this->tail);

        return $this->clientApi->sendTrytes(
            array_reverse($bundle->getTransactions()),
            $this->depth,
            $this->minWeightMagnitude
        );
    }
}
