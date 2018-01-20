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

namespace Techworker\IOTA\DI;

use Http\Client\Common\PluginClient;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Psr\Container\ContainerInterface;
use Techworker\IOTA\ClientApi\Actions\BroadcastBundle;
use Techworker\IOTA\ClientApi\Actions\FindTransactionObjects;
use Techworker\IOTA\ClientApi\Actions\GetAccountData;
use Techworker\IOTA\ClientApi\Actions\GetAddresses;
use Techworker\IOTA\ClientApi\Actions\GetBundle;
use Techworker\IOTA\ClientApi\Actions\GetBundlesFromAddresses;
use Techworker\IOTA\ClientApi\Actions\GetInputs;
use Techworker\IOTA\ClientApi\Actions\GetLatestInclusion;
use Techworker\IOTA\ClientApi\Actions\GetNewAddress;
use Techworker\IOTA\ClientApi\Actions\GetTransactionObjects;
use Techworker\IOTA\ClientApi\Actions\GetTransfers;
use Techworker\IOTA\ClientApi\Actions\IsReAttachable;
use Techworker\IOTA\ClientApi\Actions\PromoteTransaction;
use Techworker\IOTA\ClientApi\Actions\ReplayBundle;
use Techworker\IOTA\ClientApi\Actions\SendTransfer;
use Techworker\IOTA\ClientApi\Actions\SendTrytes;
use Techworker\IOTA\ClientApi\Actions\StoreAndBroadcast;
use Techworker\IOTA\ClientApi\ClientApi;
use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Cryptography\Hashing\KerlFactory;
use Techworker\IOTA\Cryptography\HMAC;
use Techworker\IOTA\Cryptography\Keccak384\Keccak384Interface;
use Techworker\IOTA\Cryptography\Keccak384\Korn;
use Techworker\IOTA\Cryptography\POW\CCurl;
use Techworker\IOTA\Cryptography\POW\PowInterface;
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
use Techworker\IOTA\RemoteApi\NodeApiClient;
use Techworker\IOTA\RemoteApi\RemoteApi;
use Techworker\IOTA\Type\HMACKey;
use Techworker\IOTA\Util\AddressUtil;
use Techworker\IOTA\Util\CheckSumUtil;

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
        $this->entries[Keccak384Interface::class] = function () use ($options) {
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
