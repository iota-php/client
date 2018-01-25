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

namespace IOTA\ClientApi;

use IOTA\ClientApi\Actions\BroadcastBundle;
use IOTA\ClientApi\Actions\FindTransactionObjects;
use IOTA\ClientApi\Actions\GetAccountData;
use IOTA\ClientApi\Actions\GetAddresses;
use IOTA\ClientApi\Actions\GetBundle;
use IOTA\ClientApi\Actions\GetBundlesFromAddresses;
use IOTA\ClientApi\Actions\GetInputs;
use IOTA\ClientApi\Actions\GetLatestInclusion;
use IOTA\ClientApi\Actions\GetNewAddress;
use IOTA\ClientApi\Actions\GetTransactionObjects;
use IOTA\ClientApi\Actions\GetTransfers;
use IOTA\ClientApi\Actions\IsReAttachable;
use IOTA\ClientApi\Actions\PromoteTransaction;
use IOTA\ClientApi\Actions\ReplayBundle;
use IOTA\ClientApi\Actions\SendTransfer;
use IOTA\ClientApi\Actions\SendTrytes;
use IOTA\ClientApi\Actions\StoreAndBroadcast;

/**
 * Class ClientApi.
 *
 * The client API wrapper for all client methods.
 */
class ClientApi
{
    use BroadcastBundle\ActionTrait,
        FindTransactionObjects\ActionTrait,
        GetAccountData\ActionTrait,
        GetAddresses\ActionTrait,
        GetBundle\ActionTrait,
        GetBundlesFromAddresses\ActionTrait,
        GetInputs\ActionTrait,
        GetLatestInclusion\ActionTrait,
        GetNewAddress\ActionTrait,
        GetTransactionObjects\ActionTrait,
        GetTransfers\ActionTrait,
        IsReAttachable\ActionTrait,
        PromoteTransaction\ActionTrait,
        SendTransfer\ActionTrait,
        SendTrytes\ActionTrait,
        StoreAndBroadcast\ActionTrait,
        ReplayBundle\ActionTrait
    {
        BroadcastBundle\ActionTrait::broadcastBundle as public;
        FindTransactionObjects\ActionTrait::findTransactionObjects as public;
        GetAccountData\ActionTrait:: getAccountData as public;
        GetAddresses\ActionTrait::getAddresses as public;
        GetBundle\ActionTrait::getBundle as public;
        GetBundlesFromAddresses\ActionTrait::getBundlesFromAddresses as public;
        GetInputs\ActionTrait::getInputs as public;
        GetLatestInclusion\ActionTrait::getLatestInclusion as public;
        GetNewAddress\ActionTrait::getNewAddress as public;
        GetTransactionObjects\ActionTrait::getTransactionObjects as public;
        GetTransfers\ActionTrait::getTransfers as public;
        IsReAttachable\ActionTrait::isReAttachable as public;
        PromoteTransaction\ActionTrait::promoteTransaction as public;
        SendTransfer\ActionTrait::sendTransfer as public;
        SendTrytes\ActionTrait::sendTrytes as public;
        StoreAndBroadcast\ActionTrait::storeAndBroadcast as public;
        ReplayBundle\ActionTrait::replayBundle as public;
    }

    /**
     * ClientApi constructor.
     *
     * @param BroadcastBundle\ActionFactory         $broadcastBundleFactory
     * @param FindTransactionObjects\ActionFactory  $findTransactionObjectsFactory
     * @param GetAccountData\ActionFactory          $getAccountDataFactory
     * @param GetAddresses\ActionFactory            $getAddressesFactory
     * @param GetBundle\ActionFactory               $getBundleFactory
     * @param GetBundlesFromAddresses\ActionFactory $getBundlesFromAddressesFactory
     * @param GetInputs\ActionFactory               $getInputsFactory
     * @param GetLatestInclusion\ActionFactory      $getLatestInclusionFactory
     * @param GetNewAddress\ActionFactory           $getNewAddressFactory
     * @param GetTransactionObjects\ActionFactory   $getTransactionObjectsFactory
     * @param GetTransfers\ActionFactory            $getTransfersFactory
     * @param IsReAttachable\ActionFactory          $isReAttachableFactory
     * @param PromoteTransaction\ActionFactory      $promoteTransactionFactory
     * @param SendTransfer\ActionFactory            $sendTransferFactory
     * @param SendTrytes\ActionFactory              $sendTrytesFactory
     * @param StoreAndBroadcast\ActionFactory       $storeAndBroadcastFactory
     * @param ReplayBundle\ActionFactory            $replayBundleFactory
     */
    public function __construct(
        BroadcastBundle\ActionFactory $broadcastBundleFactory,
        FindTransactionObjects\ActionFactory $findTransactionObjectsFactory,
        GetAccountData\ActionFactory $getAccountDataFactory,
        GetAddresses\ActionFactory $getAddressesFactory,
        GetBundle\ActionFactory $getBundleFactory,
        GetBundlesFromAddresses\ActionFactory $getBundlesFromAddressesFactory,
        GetInputs\ActionFactory $getInputsFactory,
        GetLatestInclusion\ActionFactory $getLatestInclusionFactory,
        GetNewAddress\ActionFactory $getNewAddressFactory,
        GetTransactionObjects\ActionFactory $getTransactionObjectsFactory,
        GetTransfers\ActionFactory $getTransfersFactory,
        IsReAttachable\ActionFactory $isReAttachableFactory,
        PromoteTransaction\ActionFactory $promoteTransactionFactory,
        SendTransfer\ActionFactory $sendTransferFactory,
        SendTrytes\ActionFactory $sendTrytesFactory,
        StoreAndBroadcast\ActionFactory $storeAndBroadcastFactory,
        ReplayBundle\ActionFactory $replayBundleFactory
    ) {
        $this->setBroadcastBundleFactory($broadcastBundleFactory);
        $this->setFindTransactionObjectsFactory($findTransactionObjectsFactory);
        $this->setGetAccountDataFactory($getAccountDataFactory);
        $this->setGetAddressesFactory($getAddressesFactory);
        $this->setGetBundleFactory($getBundleFactory);
        $this->setGetBundlesFromAddressesFactory($getBundlesFromAddressesFactory);
        $this->setGetInputsFactory($getInputsFactory);
        $this->setGetLatestInclusionFactory($getLatestInclusionFactory);
        $this->setGetNewAddressFactory($getNewAddressFactory);
        $this->setGetTransactionObjectsFactory($getTransactionObjectsFactory);
        $this->setGetTransfersFactory($getTransfersFactory);
        $this->setIsReAttachableFactory($isReAttachableFactory);
        $this->setPromoteTransactionFactory($promoteTransactionFactory);
        $this->setSendTransferFactory($sendTransferFactory);
        $this->setSendTrytesFactory($sendTrytesFactory);
        $this->setStoreAndBroadcastFactory($storeAndBroadcastFactory);
        $this->setReplayBundleFactory($replayBundleFactory);
    }
}
