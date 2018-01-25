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

namespace Techworker\IOTA\RemoteApi;

use Techworker\IOTA\RemoteApi\Actions\AddNeighbors;
use Techworker\IOTA\RemoteApi\Actions\AttachToTangle;
use Techworker\IOTA\RemoteApi\Actions\BroadcastTransactions;
use Techworker\IOTA\RemoteApi\Actions\FindTransactions;
use Techworker\IOTA\RemoteApi\Actions\GetBalances;
use Techworker\IOTA\RemoteApi\Actions\GetInclusionStates;
use Techworker\IOTA\RemoteApi\Actions\GetNeighbors;
use Techworker\IOTA\RemoteApi\Actions\GetNodeInfo;
use Techworker\IOTA\RemoteApi\Actions\GetTips;
use Techworker\IOTA\RemoteApi\Actions\GetTransactionsToApprove;
use Techworker\IOTA\RemoteApi\Actions\GetTrytes;
use Techworker\IOTA\RemoteApi\Actions\InterruptAttachingToTangle;
use Techworker\IOTA\RemoteApi\Actions\IsTailConsistent;
use Techworker\IOTA\RemoteApi\Actions\RemoveNeighbors;
use Techworker\IOTA\RemoteApi\Actions\StoreTransactions;

/**
 * Class RemoteApi.
 *
 * Simple wrapper around the api commands.
 */
class RemoteApi
{
    use AddNeighbors\ActionTrait,
        AttachToTangle\ActionTrait,
        BroadcastTransactions\ActionTrait,
        FindTransactions\ActionTrait,
        GetBalances\ActionTrait,
        GetInclusionStates\ActionTrait,
        GetNeighbors\ActionTrait,
        GetNodeInfo\ActionTrait,
        GetTips\ActionTrait,
        GetTransactionsToApprove\ActionTrait,
        GetTrytes\ActionTrait,
        InterruptAttachingToTangle\ActionTrait,
        IsTailConsistent\ActionTrait,
        RemoveNeighbors\ActionTrait,
        StoreTransactions\ActionTrait
    {
        AddNeighbors\ActionTrait::addNeighbors as public;
        AttachToTangle\ActionTrait::attachToTangle as public;
        BroadcastTransactions\ActionTrait::broadcastTransactions as public;
        FindTransactions\ActionTrait::findTransactions as public;
        GetBalances\ActionTrait::getBalances as public;
        GetInclusionStates\ActionTrait::getInclusionStates as public;
        GetNeighbors\ActionTrait::getNeighbors as public;
        GetNodeInfo\ActionTrait::getNodeInfo as public;
        GetTips\ActionTrait::getTips as public;
        GetTransactionsToApprove\ActionTrait::getTransactionsToApprove as public;
        GetTrytes\ActionTrait::getTrytes as public;
        InterruptAttachingToTangle\ActionTrait::interruptAttachingToTangle as public;
        IsTailConsistent\ActionTrait::isTailConsistent as public;
        RemoveNeighbors\ActionTrait::removeNeighbors as public;
        StoreTransactions\ActionTrait::storeTransactions as public;
    }

    /**
     * RemoteApi constructor.
     *
     * @param AddNeighbors\ActionFactory               $addNeighborsFactory
     * @param AttachToTangle\ActionFactory             $attachToTangleFactory
     * @param BroadcastTransactions\ActionFactory      $broadcastTransactionsFactory
     * @param FindTransactions\ActionFactory           $findTransactionsFactory
     * @param GetBalances\ActionFactory                $getBalancesFactory
     * @param GetInclusionStates\ActionFactory         $getInclusionStatesFactory
     * @param GetNeighbors\ActionFactory               $getNeighborsFactory
     * @param GetNodeInfo\ActionFactory                $getNodeInfoFactory
     * @param GetTips\ActionFactory                    $getTipsFactory
     * @param GetTransactionsToApprove\ActionFactory   $getTransactionsToApproveFactory
     * @param GetTrytes\ActionFactory                  $getTrytesFactory
     * @param InterruptAttachingToTangle\ActionFactory $interruptAttachingToTangleFactory
     * @param IsTailConsistent\ActionFactory           $isTailConsistentFactory
     * @param RemoveNeighbors\ActionFactory            $removeNeighborsFactory
     * @param StoreTransactions\ActionFactory          $storeTransactionsFactory
     */
    public function __construct(
        AddNeighbors\ActionFactory $addNeighborsFactory,
        AttachToTangle\ActionFactory $attachToTangleFactory,
        BroadcastTransactions\ActionFactory $broadcastTransactionsFactory,
        FindTransactions\ActionFactory $findTransactionsFactory,
        GetBalances\ActionFactory $getBalancesFactory,
        GetInclusionStates\ActionFactory $getInclusionStatesFactory,
        GetNeighbors\ActionFactory $getNeighborsFactory,
        GetNodeInfo\ActionFactory $getNodeInfoFactory,
        GetTips\ActionFactory $getTipsFactory,
        GetTransactionsToApprove\ActionFactory $getTransactionsToApproveFactory,
        GetTrytes\ActionFactory $getTrytesFactory,
        InterruptAttachingToTangle\ActionFactory $interruptAttachingToTangleFactory,
        IsTailConsistent\ActionFactory $isTailConsistentFactory,
        RemoveNeighbors\ActionFactory $removeNeighborsFactory,
        StoreTransactions\ActionFactory $storeTransactionsFactory
    ) {
        $this->setAddNeighborsFactory($addNeighborsFactory);
        $this->setAttachToTangleFactory($attachToTangleFactory);
        $this->setBroadcastTransactionsFactory($broadcastTransactionsFactory);
        $this->setFindTransactionsFactory($findTransactionsFactory);
        $this->setGetBalancesFactory($getBalancesFactory);
        $this->setGetInclusionStatesFactory($getInclusionStatesFactory);
        $this->setGetNeighborsFactory($getNeighborsFactory);
        $this->setGetNodeInfoFactory($getNodeInfoFactory);
        $this->setGetTipsFactory($getTipsFactory);
        $this->setGetTransactionsToApproveFactory($getTransactionsToApproveFactory);
        $this->setGetTrytesFactory($getTrytesFactory);
        $this->setInterruptAttachingToTangleFactory($interruptAttachingToTangleFactory);
        $this->setIsTailConsistentFactory($isTailConsistentFactory);
        $this->setRemoveNeighborsFactory($removeNeighborsFactory);
        $this->setStoreTransactionsFactory($storeTransactionsFactory);
    }
}
