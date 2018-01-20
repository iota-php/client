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

namespace IOTA\DI;

use Http\Client\Common\PluginClient;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use IOTA\Client;
use Psr\Container\ContainerInterface;
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
use IOTA\ClientApi\ClientApi;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Cryptography\HMAC;
use IOTA\Cryptography\Keccak384\Keccak384Interface;
use IOTA\Cryptography\Keccak384\Korn;
use IOTA\Cryptography\POW\CCurl;
use IOTA\Cryptography\POW\PowInterface;
use IOTA\RemoteApi\Actions\AddNeighbors;
use IOTA\RemoteApi\Actions\AttachToTangle;
use IOTA\RemoteApi\Actions\BroadcastTransactions;
use IOTA\RemoteApi\Actions\FindTransactions;
use IOTA\RemoteApi\Actions\GetBalances;
use IOTA\RemoteApi\Actions\GetInclusionStates;
use IOTA\RemoteApi\Actions\GetNeighbors;
use IOTA\RemoteApi\Actions\GetNodeInfo;
use IOTA\RemoteApi\Actions\GetTips;
use IOTA\RemoteApi\Actions\GetTransactionsToApprove;
use IOTA\RemoteApi\Actions\GetTrytes;
use IOTA\RemoteApi\Actions\InterruptAttachingToTangle;
use IOTA\RemoteApi\Actions\IsTailConsistent;
use IOTA\RemoteApi\Actions\RemoveNeighbors;
use IOTA\RemoteApi\Actions\StoreTransactions;
use IOTA\RemoteApi\NodeApiClient;
use IOTA\RemoteApi\RemoteApi;
use IOTA\Type\HMACKey;
use IOTA\Util\AddressUtil;
use IOTA\Util\CheckSumUtil;

/**
 * Class IOTAContainer.
 *
 * A simple PSR-11 container implementation.
 */
class IOTAContainer implements ContainerInterface
{
    /**
     * The list of entries in the container.
     *
     * @var array
     */
    protected $entries;

    /**
     * IOTAContainer constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $factories = [
            AddNeighbors\ActionFactory::class,
            AttachToTangle\ActionFactory::class,
            BroadcastTransactions\ActionFactory::class,
            FindTransactions\ActionFactory::class,
            GetBalances\ActionFactory::class,
            GetInclusionStates\ActionFactory::class,
            GetNeighbors\ActionFactory::class,
            GetNodeInfo\ActionFactory::class,
            GetTips\ActionFactory::class,
            GetTransactionsToApprove\ActionFactory::class,
            GetTrytes\ActionFactory::class,
            InterruptAttachingToTangle\ActionFactory::class,
            IsTailConsistent\ActionFactory::class,
            RemoveNeighbors\ActionFactory::class,
            StoreTransactions\ActionFactory::class,
            BroadcastBundle\ActionFactory::class,
            FindTransactionObjects\ActionFactory::class,
            GetAccountData\ActionFactory::class,
            GetAddresses\ActionFactory::class,
            GetBundle\ActionFactory::class,
            GetBundlesFromAddresses\ActionFactory::class,
            GetInputs\ActionFactory::class,
            GetLatestInclusion\ActionFactory::class,
            GetNewAddress\ActionFactory::class,
            GetTransactionObjects\ActionFactory::class,
            GetTransfers\ActionFactory::class,
            IsReAttachable\ActionFactory::class,
            PromoteTransaction\ActionFactory::class,
            SendTransfer\ActionFactory::class,
            SendTrytes\ActionFactory::class,
            StoreAndBroadcast\ActionFactory::class,
            ReplayBundle\ActionFactory::class,
        ];

        foreach ($factories as $factory) {
            $this->entries[$factory] = function () use ($factory) {
                return new $factory($this);
            };
        }

        // httplug
        $this->entries[MessageFactory::class] = function() use($options) : MessageFactory {
            if(isset($options['http'][MessageFactory::class])) {
                return $options['http'][MessageFactory::class];
            }
            return MessageFactoryDiscovery::find();
        };

        $this->entries[HttpAsyncClient::class] = function() use($options) : HttpAsyncClient {
            if(isset($options['http'][HttpAsyncClient::class])) {
                return $options[HttpAsyncClient::class];
            }
            return new PluginClient(
                HttpAsyncClientDiscovery::find(),
                $options['http']['plugins'] ?? [],
                $options['http']['options'] ?? []
            );
        };

        $this->entries[HttpClient::class] = function() use($options) : HttpClient {
            if(isset($options['http'][HttpClient::class])) {
                return $options[HttpClient::class];
            }
            return new PluginClient(
                HttpClientDiscovery::find(),
                $options['http']['plugins'] ?? [],
                $options['http']['options'] ?? []
            );
        };

        $this->entries[NodeApiClient::class] = function() : NodeApiClient{
            return new NodeApiClient(
                $this->get(HttpClient::class),
                $this->get(HttpAsyncClient::class),
                $this->get(MessageFactory::class)
            );
        };

        // the keccak 384 implementation
        $this->entries[Keccak384Interface::class] = function () {
            //return new NodeJS($options['keccak384-nodejs']);
            return new Korn();
        };

        // returns a kerl factory
        $this->entries[KerlFactory::class] = function () {
            return new KerlFactory($this->get(Keccak384Interface::class));
        };

        $this->entries[PowInterface::class] = function () use ($options) {
            return new CCurl($options['ccurlPath']);
        };

        $this->entries[CurlFactory::class] = function () {
            return new CurlFactory();
        };

        $this->entries[HMAC::class] = function () {
            return function (HMACKey $hmacKey) {
                return new HMAC(27, $hmacKey, $this->get(CurlFactory::class));
            };
        };

        $this->entries[CheckSumUtil::class] = function () {
            return new CheckSumUtil($this->get(KerlFactory::class));
        };

        $this->entries[AddressUtil::class] = function () {
            return new AddressUtil(
                $this->get(KerlFactory::class),
                $this->get(CheckSumUtil::class)
            );
        };

        $this->entries[ClientApi::class] = function () {
            return new ClientApi(
                $this->get(BroadcastBundle\ActionFactory::class),
                $this->get(FindTransactionObjects\ActionFactory::class),
                $this->get(GetAccountData\ActionFactory::class),
                $this->get(GetAddresses\ActionFactory::class),
                $this->get(GetBundle\ActionFactory::class),
                $this->get(GetBundlesFromAddresses\ActionFactory::class),
                $this->get(GetInputs\ActionFactory::class),
                $this->get(GetLatestInclusion\ActionFactory::class),
                $this->get(GetNewAddress\ActionFactory::class),
                $this->get(GetTransactionObjects\ActionFactory::class),
                $this->get(GetTransfers\ActionFactory::class),
                $this->get(IsReAttachable\ActionFactory::class),
                $this->get(PromoteTransaction\ActionFactory::class),
                $this->get(SendTransfer\ActionFactory::class),
                $this->get(SendTrytes\ActionFactory::class),
                $this->get(StoreAndBroadcast\ActionFactory::class),
                $this->get(ReplayBundle\ActionFactory::class)
            );
        };

        $this->entries[RemoteApi::class] = function () {
            return new RemoteApi(
                $this->get(AddNeighbors\ActionFactory::class),
                $this->get(AttachToTangle\ActionFactory::class),
                $this->get(BroadcastTransactions\ActionFactory::class),
                $this->get(FindTransactions\ActionFactory::class),
                $this->get(GetBalances\ActionFactory::class),
                $this->get(GetInclusionStates\ActionFactory::class),
                $this->get(GetNeighbors\ActionFactory::class),
                $this->get(GetNodeInfo\ActionFactory::class),
                $this->get(GetTips\ActionFactory::class),
                $this->get(GetTransactionsToApprove\ActionFactory::class),
                $this->get(GetTrytes\ActionFactory::class),
                $this->get(InterruptAttachingToTangle\ActionFactory::class),
                $this->get(IsTailConsistent\ActionFactory::class),
                $this->get(RemoveNeighbors\ActionFactory::class),
                $this->get(StoreTransactions\ActionFactory::class)
            );
        };

        $this->entries[BroadcastBundle\ActionFactory::class] = function () {
            return new BroadcastBundle\ActionFactory($this);
        };

        $this->entries[Client::class] = function () use ($options) {
            return new Client($this->get(RemoteApi::class), $this->get(ClientApi::class), $options['nodes']);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!isset($this->entries[$id])) {
            throw new NotFoundException('Unknown ident '.$id);
        }

        return $this->entries[$id]();
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return isset($this->entries[$id]);
    }
}
