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
use Techworker\IOTA\Cryptography\Keccak384\NodeJS;
use Techworker\IOTA\Cryptography\POW\CCurl;
use Techworker\IOTA\Cryptography\POW\PowInterface;
use Techworker\IOTA\IOTA;
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
use Techworker\IOTA\RemoteApi\Commands\IsTailConsistent;
use Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors;
use Techworker\IOTA\RemoteApi\Commands\StoreTransactions;
use Techworker\IOTA\RemoteApi\HttpClient\GuzzleClient;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
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
            AddNeighbors\RequestFactory::class,
            AttachToTangle\RequestFactory::class,
            BroadcastTransactions\RequestFactory::class,
            FindTransactions\RequestFactory::class,
            GetBalances\RequestFactory::class,
            GetInclusionStates\RequestFactory::class,
            GetNeighbors\RequestFactory::class,
            GetNodeInfo\RequestFactory::class,
            GetTips\RequestFactory::class,
            GetTransactionsToApprove\RequestFactory::class,
            GetTrytes\RequestFactory::class,
            InterruptAttachingToTangle\RequestFactory::class,
            IsTailConsistent\RequestFactory::class,
            RemoveNeighbors\RequestFactory::class,
            StoreTransactions\RequestFactory::class,
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

        // the http client used.
        $this->entries[HttpClientInterface::class] = function () {
            return new GuzzleClient();
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
                $this->get(AddNeighbors\RequestFactory::class),
                $this->get(AttachToTangle\RequestFactory::class),
                $this->get(BroadcastTransactions\RequestFactory::class),
                $this->get(FindTransactions\RequestFactory::class),
                $this->get(GetBalances\RequestFactory::class),
                $this->get(GetInclusionStates\RequestFactory::class),
                $this->get(GetNeighbors\RequestFactory::class),
                $this->get(GetNodeInfo\RequestFactory::class),
                $this->get(GetTips\RequestFactory::class),
                $this->get(GetTransactionsToApprove\RequestFactory::class),
                $this->get(GetTrytes\RequestFactory::class),
                $this->get(InterruptAttachingToTangle\RequestFactory::class),
                $this->get(IsTailConsistent\RequestFactory::class),
                $this->get(RemoveNeighbors\RequestFactory::class),
                $this->get(StoreTransactions\RequestFactory::class)
            );
        };

        $this->entries[BroadcastBundle\ActionFactory::class] = function () {
            return new BroadcastBundle\ActionFactory($this);
        };

        $this->entries[IOTA::class] = function () use ($options) {
            return new IOTA($this->get(RemoteApi::class), $this->get(ClientApi::class), $options['nodes']);
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
