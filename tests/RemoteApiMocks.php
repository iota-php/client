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

namespace IOTA\Tests;

use PHPUnit\Framework\TestCase;
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
use IOTA\RemoteApi\Actions\RemoveNeighbors;
use IOTA\RemoteApi\Actions\StoreTransactions;
use IOTA\Type\TransactionHash;

/**
 * Class ClientApiMocks.
 *
 * A collection of mocked client API actions and factories.
 */
class RemoteApiMocks
{
    /**
     * The testcase of the test to get easy access to the mocking methods
     * in phpunit.
     *
     * @var TestCase
     */
    protected $testCase;

    /**
     * RemoteApiMocks constructor.
     *
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param null|AddNeighbors\Response $executeResponse
     *
     * @return AddNeighbors\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function addNeighborsRequest(AddNeighbors\Response $executeResponse = null): AddNeighbors\Request
    {
        $executeResponse = $executeResponse ??
            (new AddNeighbors\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(AddNeighbors\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request AddNeighbors\Request
        return $request;
    }

    /**
     * @param null|AddNeighbors\Request $request
     *
     * @return AddNeighbors\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function addNeighborsFactory(AddNeighbors\Request $request = null): AddNeighbors\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(AddNeighbors\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory AddNeighbors\RequestFactory
        return $factory;
    }

    /**
     * @param null|AttachToTangle\Response $executeResponse
     *
     * @return AttachToTangle\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function attachToTangleRequest(AttachToTangle\Response $executeResponse = null): AttachToTangle\Request
    {
        $executeResponse = $executeResponse ??
            (new AttachToTangle\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(AttachToTangle\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request AttachToTangle\Request
        return $request;
    }

    /**
     * @param null|AttachToTangle\Request $request
     *
     * @return AttachToTangle\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function attachToTangleFactory(AttachToTangle\Request $request = null): AttachToTangle\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(AttachToTangle\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory AttachToTangle\RequestFactory
        return $factory;
    }

    /**
     * @param null|BroadcastTransactions\Response $executeResponse
     *
     * @return BroadcastTransactions\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function broadcastTransactionsRequest(BroadcastTransactions\Response $executeResponse = null): BroadcastTransactions\Request
    {
        $executeResponse = $executeResponse ??
            (new BroadcastTransactions\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(BroadcastTransactions\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request BroadcastTransactions\Request
        return $request;
    }

    /**
     * @param null|BroadcastTransactions\Request $request
     *
     * @return BroadcastTransactions\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function broadcastTransactionsFactory(BroadcastTransactions\Request $request = null): BroadcastTransactions\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(BroadcastTransactions\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory BroadcastTransactions\RequestFactory
        return $factory;
    }

    /**
     * @param null|FindTransactions\Response $executeResponse
     *
     * @return FindTransactions\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function findTransactionsRequest(FindTransactions\Response $executeResponse = null): FindTransactions\Request
    {
        $executeResponse = $executeResponse ??
            (new FindTransactions\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(FindTransactions\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request FindTransactions\Request
        return $request;
    }

    /**
     * @param TransactionHash[] $hashes
     *
     * @return FindTransactions\Response
     */
    public function findTransactionsResponse(array $hashes): FindTransactions\Response
    {
        return (new FindTransactions\Response())->initialize(200, json_encode([
            'hashes' => array_map('strval', $hashes),
        ]));
    }

    /**
     * @param null|FindTransactions\Request $request
     *
     * @return FindTransactions\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function findTransactionsFactory(FindTransactions\Request $request = null): FindTransactions\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(FindTransactions\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory FindTransactions\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetBalances\Response $executeResponse
     *
     * @return GetBalances\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getBalancesRequest(GetBalances\Response $executeResponse = null): GetBalances\Request
    {
        $executeResponse = $executeResponse ??
            (new GetBalances\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetBalances\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetBalances\Request
        return $request;
    }

    /**
     * @param null|GetBalances\Request $request
     *
     * @return GetBalances\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getBalancesFactory(GetBalances\Request $request = null): GetBalances\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetBalances\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetBalances\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetInclusionStates\Response $executeResponse
     *
     * @return GetInclusionStates\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getInclusionStatesRequest(GetInclusionStates\Response $executeResponse = null): GetInclusionStates\Request
    {
        $executeResponse = $executeResponse ??
            (new GetInclusionStates\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetInclusionStates\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetInclusionStates\Request
        return $request;
    }

    /**
     * @param null|GetInclusionStates\Request $request
     *
     * @return GetInclusionStates\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getInclusionStatesFactory(GetInclusionStates\Request $request = null): GetInclusionStates\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetInclusionStates\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetInclusionStates\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetNeighbors\Response $executeResponse
     *
     * @return GetNeighbors\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNeighborsRequest(GetNeighbors\Response $executeResponse = null): GetNeighbors\Request
    {
        $executeResponse = $executeResponse ??
            (new GetNeighbors\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetNeighbors\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetNeighbors\Request
        return $request;
    }

    /**
     * @param null|GetNeighbors\Request $request
     *
     * @return GetNeighbors\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNeighborsFactory(GetNeighbors\Request $request = null): GetNeighbors\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetNeighbors\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetNeighbors\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetNodeInfo\Response $executeResponse
     *
     * @return GetNodeInfo\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNodeInfoRequest(GetNodeInfo\Response $executeResponse = null): GetNodeInfo\Request
    {
        $executeResponse = $executeResponse ??
            (new GetNodeInfo\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetNodeInfo\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetNodeInfo\Request
        return $request;
    }

    /**
     * @param null|GetNodeInfo\Request $request
     *
     * @return GetNodeInfo\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNodeInfoFactory(GetNodeInfo\Request $request = null): GetNodeInfo\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetNodeInfo\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetNodeInfo\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetTips\Response $executeResponse
     *
     * @return GetTips\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTipsRequest(GetTips\Response $executeResponse = null): GetTips\Request
    {
        $executeResponse = $executeResponse ??
            (new GetTips\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetTips\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetTips\Request
        return $request;
    }

    /**
     * @param null|GetTips\Request $request
     *
     * @return GetTips\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTipsFactory(GetTips\Request $request = null): GetTips\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetTips\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetTips\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetTransactionsToApprove\Response $executeResponse
     *
     * @return GetTransactionsToApprove\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTransactionsToApproveRequest(GetTransactionsToApprove\Response $executeResponse = null): GetTransactionsToApprove\Request
    {
        $executeResponse = $executeResponse ??
            (new GetTransactionsToApprove\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetTransactionsToApprove\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetTransactionsToApprove\Request
        return $request;
    }

    /**
     * @param null|GetTransactionsToApprove\Request $request
     *
     * @return GetTransactionsToApprove\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTransactionsToApproveFactory(GetTransactionsToApprove\Request $request = null): GetTransactionsToApprove\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetTransactionsToApprove\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetTransactionsToApprove\RequestFactory
        return $factory;
    }

    /**
     * @param null|GetTrytes\Response $executeResponse
     *
     * @return GetTrytes\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTrytesRequest(GetTrytes\Response $executeResponse = null): GetTrytes\Request
    {
        $executeResponse = $executeResponse ??
            (new GetTrytes\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(GetTrytes\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request GetTrytes\Request
        return $request;
    }

    /**
     * @param null|GetTrytes\Request $request
     *
     * @return GetTrytes\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTrytesToApproveFactory(GetTrytes\Request $request = null): GetTrytes\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(GetTrytes\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory GetTrytes\RequestFactory
        return $factory;
    }

    /**
     * @param null|InterruptAttachingToTangle\Response $executeResponse
     *
     * @return InterruptAttachingToTangle\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    public function interruptAttachingToTangleRequest(InterruptAttachingToTangle\Response $executeResponse = null): InterruptAttachingToTangle\Request
    {
        $executeResponse = $executeResponse ??
            (new InterruptAttachingToTangle\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(InterruptAttachingToTangle\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request InterruptAttachingToTangle\Request
        return $request;
    }

    /**
     * @param null|InterruptAttachingToTangle\Request $request
     *
     * @return InterruptAttachingToTangle\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function interruptAttachingToTangleFactory(InterruptAttachingToTangle\Request $request = null): InterruptAttachingToTangle\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(InterruptAttachingToTangle\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory InterruptAttachingToTangle\RequestFactory
        return $factory;
    }

    /**
     * @param null|RemoveNeighbors\Response $executeResponse
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|RemoveNeighbors\Request
     */
    public function removeNeighborsRequest(RemoveNeighbors\Response $executeResponse = null): RemoveNeighbors\Request
    {
        $executeResponse = $executeResponse ??
            (new RemoveNeighbors\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(RemoveNeighbors\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request RemoveNeighbors\Request
        return $request;
    }

    /**
     * @param null|RemoveNeighbors\Request $request
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|RemoveNeighbors\RequestFactory
     */
    public function removeNeighborsFactory(RemoveNeighbors\Request $request = null): RemoveNeighbors\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(RemoveNeighbors\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory RemoveNeighbors\RequestFactory
        return $factory;
    }

    /**
     * @param null|StoreTransactions\Response $executeResponse
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|StoreTransactions\Request
     */
    public function storeTransactionsRequest(StoreTransactions\Response $executeResponse = null): StoreTransactions\Request
    {
        $executeResponse = $executeResponse ??
            (new StoreTransactions\Response())->initialize(200, '{}');

        $request = $this->testCase->getMockBuilder(StoreTransactions\Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $request->method('execute')->willReturn($executeResponse);

        // @var $request StoreTransactions\Request
        return $request;
    }

    /**
     * @param null|StoreTransactions\Request $request
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|StoreTransactions\RequestFactory
     */
    public function storeTransactionsFactory(StoreTransactions\Request $request = null): StoreTransactions\RequestFactory
    {
        $request = $request ?? $this->broadcastTransactionsRequest();
        $factory = $this->testCase->getMockBuilder(StoreTransactions\RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($request);

        // @var $factory StoreTransactions\RequestFactory
        return $factory;
    }
}
