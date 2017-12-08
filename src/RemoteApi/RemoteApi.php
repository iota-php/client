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

namespace Techworker\IOTA\RemoteApi;

use Techworker\IOTA\RemoteApi\Commands\AddNeighbors;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle;
use Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions;
use Techworker\IOTA\RemoteApi\Commands\FindTransactions;
use Techworker\IOTA\RemoteApi\Commands\GetBalances;
use Techworker\IOTA\RemoteApi\Commands\GetInclusionStates;
use Techworker\IOTA\RemoteApi\Commands\GetNeighbors;
use Techworker\IOTA\RemoteApi\Commands\GetNodeInfo;
use Techworker\IOTA\RemoteApi\Commands\GetTips;
use Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove;
use Techworker\IOTA\RemoteApi\Commands\GetTrytes;
use Techworker\IOTA\RemoteApi\Commands\InterruptAttachingToTangle;
use Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors;
use Techworker\IOTA\RemoteApi\Commands\StoreTransactions;

/**
 * Class RemoteApi.
 *
 * Simple wrapper around the api commands.
 */
class RemoteApi
{
    use AddNeighbors\RequestTrait,
        AttachToTangle\RequestTrait,
        BroadcastTransactions\RequestTrait,
        FindTransactions\RequestTrait,
        GetBalances\RequestTrait,
        GetInclusionStates\RequestTrait,
        GetNeighbors\RequestTrait,
        GetNodeInfo\RequestTrait,
        GetTips\RequestTrait,
        GetTransactionsToApprove\RequestTrait,
        GetTrytes\RequestTrait,
        InterruptAttachingToTangle\RequestTrait,
        RemoveNeighbors\RequestTrait,
        StoreTransactions\RequestTrait
    {
        AddNeighbors\RequestTrait::addNeighbors as public;
        AttachToTangle\RequestTrait::attachToTangle as public;
        BroadcastTransactions\RequestTrait::broadcastTransactions as public;
        FindTransactions\RequestTrait::findTransactions as public;
        GetBalances\RequestTrait::getBalances as public;
        GetInclusionStates\RequestTrait::getInclusionStates as public;
        GetNeighbors\RequestTrait::getNeighbors as public;
        GetNodeInfo\RequestTrait::getNodeInfo as public;
        GetTips\RequestTrait::getTips as public;
        GetTransactionsToApprove\RequestTrait::getTransactionsToApprove as public;
        GetTrytes\RequestTrait::getTrytes as public;
        InterruptAttachingToTangle\RequestTrait::interruptAttachingToTangle as public;
        RemoveNeighbors\RequestTrait::removeNeighbors as public;
        StoreTransactions\RequestTrait::storeTransactions as public;
    }

    /**
     * RemoteApi constructor.
     *
     * @param AddNeighbors\RequestFactory               $addNeighborsFactory
     * @param AttachToTangle\RequestFactory             $attachToTangleFactory
     * @param BroadcastTransactions\RequestFactory      $broadcastTransactionsFactory
     * @param FindTransactions\RequestFactory           $findTransactionsFactory
     * @param GetBalances\RequestFactory                $getBalancesFactory
     * @param GetInclusionStates\RequestFactory         $getInclusionStatesFactory
     * @param GetNeighbors\RequestFactory               $getNeighborsFactory
     * @param GetNodeInfo\RequestFactory                $getNodeInfoFactory
     * @param GetTips\RequestFactory                    $getTipsFactory
     * @param GetTransactionsToApprove\RequestFactory   $getTransactionsToApproveFactory
     * @param GetTrytes\RequestFactory                  $getTrytesFactory
     * @param InterruptAttachingToTangle\RequestFactory $interruptAttachingToTangleFactory
     * @param RemoveNeighbors\RequestFactory            $removeNeighborsFactory
     * @param StoreTransactions\RequestFactory          $storeTransactionsFactory
     */
    public function __construct(
        AddNeighbors\RequestFactory $addNeighborsFactory,
        AttachToTangle\RequestFactory $attachToTangleFactory,
        BroadcastTransactions\RequestFactory $broadcastTransactionsFactory,
        FindTransactions\RequestFactory $findTransactionsFactory,
        GetBalances\RequestFactory $getBalancesFactory,
        GetInclusionStates\RequestFactory $getInclusionStatesFactory,
        GetNeighbors\RequestFactory $getNeighborsFactory,
        GetNodeInfo\RequestFactory $getNodeInfoFactory,
        GetTips\RequestFactory $getTipsFactory,
        GetTransactionsToApprove\RequestFactory $getTransactionsToApproveFactory,
        GetTrytes\RequestFactory $getTrytesFactory,
        InterruptAttachingToTangle\RequestFactory $interruptAttachingToTangleFactory,
        RemoveNeighbors\RequestFactory $removeNeighborsFactory,
        StoreTransactions\RequestFactory $storeTransactionsFactory
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
        $this->setRemoveNeighborsFactory($removeNeighborsFactory);
        $this->setStoreTransactionsFactory($storeTransactionsFactory);
    }
}
