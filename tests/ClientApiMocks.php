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

namespace Techworker\IOTA\Tests;

use PHPUnit\Framework\TestCase;
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
use Techworker\IOTA\ClientApi\Actions\IsReattachable;
use Techworker\IOTA\ClientApi\Actions\SendTrytes;
use Techworker\IOTA\ClientApi\Actions\StoreAndBroadcast;
use Techworker\IOTA\ClientApi\VoidResult;

/**
 * Class ClientApiMocks.
 *
 * A collection of mocked client API actions and factories.
 */
class ClientApiMocks
{
    /**
     * The testcase of the test to get easy access to the mocking methods
     * in phpunit.
     *
     * @var TestCase
     */
    protected $testCase;

    /**
     * ClientApiMocks constructor.
     *
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param null|GetBundle\Result $executeResult
     *
     * @return GetBundle\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getBundleAction(GetBundle\Result $executeResult = null): GetBundle\Action
    {
        $executeResult = $executeResult ??
            (new GetBundle\Result())->setBundle(DummyData::getBundle());

        $getBundleAction = $this->testCase->getMockBuilder(GetBundle\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $getBundleAction->method('execute')->willReturn($executeResult);

        // @var GetBundle\Action $getBundleAction
        return $getBundleAction;
    }

    /**
     * @param GetBundle\Action $action
     *
     * @return GetBundle\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getBundleFactory(GetBundle\Action $action = null): GetBundle\ActionFactory
    {
        $action = $action ?? $this->getBundleAction();
        $getBundleActionFactory = $this->testCase->getMockBuilder(GetBundle\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $getBundleActionFactory->method('factory')->willReturn($action);

        // @var GetBundle\ActionFactory $getBundleActionFactory
        return $getBundleActionFactory;
    }

    /**
     * @param null|GetBundlesFromAddresses\Result $executeResult
     *
     * @return GetBundlesFromAddresses\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getBundlesFromAddressesAction(GetBundlesFromAddresses\Result $executeResult = null): GetBundlesFromAddresses\Action
    {
        $executeResult = $executeResult ??
            (new GetBundlesFromAddresses\Result())->setBundle(DummyData::getBundle());

        $getBundleAction = $this->testCase->getMockBuilder(GetBundlesFromAddresses\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $getBundleAction->method('execute')->willReturn($executeResult);

        // @var GetBundlesFromAddresses\Action $getBundleAction
        return $getBundleAction;
    }

    /**
     * @param GetBundlesFromAddresses\Action $action
     *
     * @return GetBundlesFromAddresses\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getBundlesFromAddressesFactory(GetBundlesFromAddresses\Action $action = null): GetBundlesFromAddresses\ActionFactory
    {
        $action = $action ?? $this->getBundleAction();
        $getBundleActionFactory = $this->testCase->getMockBuilder(GetBundlesFromAddresses\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $getBundleActionFactory->method('factory')->willReturn($action);

        // @var GetBundlesFromAddresses\ActionFactory $getBundleActionFactory
        return $getBundleActionFactory;
    }

    /**
     * @param null|FindTransactionObjects\Result $executeResult
     *
     * @return FindTransactionObjects\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function findTransactionObjectsAction(FindTransactionObjects\Result $executeResult = null): FindTransactionObjects\Action
    {
        $executeResult = $executeResult ??
            (new FindTransactionObjects\Result())->addTransaction(DummyData::getTransaction());

        $action = $this->testCase->getMockBuilder(FindTransactionObjects\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var FindTransactionObjects\Action $action
        return $action;
    }

    /**
     * @param FindTransactionObjects\Action $action
     *
     * @return FindTransactionObjects\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function findTransactionObjectsFactory(FindTransactionObjects\Action $action = null): FindTransactionObjects\ActionFactory
    {
        $action = $action ?? $this->findTransactionObjectsAction();

        $factory = $this->testCase->getMockBuilder(FindTransactionObjects\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var FindTransactionObjects\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetAccountData\Result $executeResult
     *
     * @return GetAccountData\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getAccountDataAction(GetAccountData\Result $executeResult = null): GetAccountData\Action
    {
        $executeResult = $executeResult ??
            (new GetAccountData\Result())->setAccountData(DummyData::getAccountData());

        $action = $this->testCase->getMockBuilder(GetAccountData\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetAccountData\Action $action
        return $action;
    }

    /**
     * @param GetAccountData\Action $action
     *
     * @return GetAccountData\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getAccountDataFactory(GetAccountData\Action $action = null): GetAccountData\ActionFactory
    {
        $action = $action ?? $this->getAccountDataAction();

        $factory = $this->testCase->getMockBuilder(GetAccountData\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetAccountData\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetAddresses\Result $executeResult
     *
     * @return GetAddresses\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getAddressesAction(GetAddresses\Result $executeResult = null): GetAddresses\Action
    {
        $executeResult = $executeResult ??
            (new GetAddresses\Result())->addAddress(DummyData::getAddress(), 0);

        $action = $this->testCase->getMockBuilder(GetAddresses\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetAddresses\Action $action
        return $action;
    }

    /**
     * @param GetAddresses\Action $action
     *
     * @return GetAddresses\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getAddressesFactory(GetAddresses\Action $action = null): GetAddresses\ActionFactory
    {
        $action = $action ?? $this->getAddressesAction();

        $factory = $this->testCase->getMockBuilder(GetAddresses\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetAddresses\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetInputs\Result $executeResult
     *
     * @return GetInputs\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getInputsAction(GetInputs\Result $executeResult = null): GetInputs\Action
    {
        $executeResult = $executeResult ??
            (new GetInputs\Result())->addInput(DummyData::getInput());

        $action = $this->testCase->getMockBuilder(GetInputs\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetInputs\Action $action
        return $action;
    }

    /**
     * @param GetInputs\Action $action
     *
     * @return GetInputs\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getInputsFactory(GetInputs\Action $action = null): GetInputs\ActionFactory
    {
        $action = $action ?? $this->getInputsAction();

        $factory = $this->testCase->getMockBuilder(GetInputs\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetInputs\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetLatestInclusion\Result $executeResult
     *
     * @return GetLatestInclusion\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getLatestInclusionAction(GetLatestInclusion\Result $executeResult = null): GetLatestInclusion\Action
    {
        $executeResult = $executeResult ??
            (new GetLatestInclusion\Result())->addState(DummyData::getTransactionHash(), true);

        $action = $this->testCase->getMockBuilder(GetLatestInclusion\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetLatestInclusion\Action $action
        return $action;
    }

    /**
     * @param GetLatestInclusion\Action $action
     *
     * @return GetLatestInclusion\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getLatestInclusionFactory(GetLatestInclusion\Action $action = null): GetLatestInclusion\ActionFactory
    {
        $action = $action ?? $this->getLatestInclusionAction();

        $factory = $this->testCase->getMockBuilder(GetLatestInclusion\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetLatestInclusion\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetNewAddress\Result $executeResult
     *
     * @return GetNewAddress\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNewAddressAction(GetNewAddress\Result $executeResult = null): GetNewAddress\Action
    {
        $executeResult = $executeResult ??
            (new GetNewAddress\Result())
                ->addPassedAddress(DummyData::getAddress(), 0)
                ->setAddress(DummyData::getAddress())
                ->setIndex(1);

        $action = $this->testCase->getMockBuilder(GetNewAddress\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetNewAddress\Action $action
        return $action;
    }

    /**
     * @param GetNewAddress\Action $action
     *
     * @return GetNewAddress\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getNewAddressFactory(GetNewAddress\Action $action = null): GetNewAddress\ActionFactory
    {
        $action = $action ?? $this->getNewAddressAction();
        $factory = $this->testCase->getMockBuilder(GetNewAddress\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetNewAddress\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetTransactionObjects\Result $executeResult
     *
     * @return GetTransactionObjects\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTransactionObjectsAction(GetTransactionObjects\Result $executeResult = null, array $methods = []): GetTransactionObjects\Action
    {
        $executeResult = $executeResult ??
            (new GetTransactionObjects\Result())
                ->addTransaction(DummyData::getTransaction());

        $action = $this->testCase->getMockBuilder(GetTransactionObjects\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(array_merge(['execute'], $methods))
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetTransactionObjects\Action $action
        return $action;
    }

    /**
     * @param GetTransactionObjects\Action $action
     *
     * @return GetTransactionObjects\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTransactionObjectsFactory(GetTransactionObjects\Action $action = null): GetTransactionObjects\ActionFactory
    {
        $action = $action ?? $this->getTransactionObjectsAction();

        $factory = $this->testCase->getMockBuilder(GetTransactionObjects\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetTransactionObjects\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|GetTransfers\Result $executeResult
     *
     * @return GetTransfers\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTransfersAction(GetTransfers\Result $executeResult = null): GetTransfers\Action
    {
        $executeResult = $executeResult ??
            (new GetTransfers\Result())
                ->setBundles([DummyData::getBundle()]);

        $action = $this->testCase->getMockBuilder(GetTransfers\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var GetTransfers\Action $action
        return $action;
    }

    /**
     * @param GetTransfers\Action $action
     *
     * @return GetTransfers\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getTransfersFactory(GetTransfers\Action $action = null): GetTransfers\ActionFactory
    {
        $action = $action ?? $this->getTransfersAction();

        $factory = $this->testCase->getMockBuilder(GetTransfers\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var GetTransfers\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|IsReattachable\Result $executeResult
     *
     * @return IsReattachable\Action|\PHPUnit_Framework_MockObject_MockObject
     */
    public function isReattachableAction(IsReattachable\Result $executeResult = null): IsReattachable\Action
    {
        $executeResult = $executeResult ??
            (new IsReattachable\Result())
                ->setStates([true]);

        $action = $this->testCase->getMockBuilder(IsReattachable\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var IsReattachable\Action $action
        return $action;
    }

    /**
     * @param IsReattachable\Action $action
     *
     * @return IsReattachable\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    public function isReattachableFactory(IsReattachable\Action $action = null): IsReattachable\ActionFactory
    {
        $action = $action ?? $this->isReattachableAction();

        $factory = $this->testCase->getMockBuilder(IsReattachable\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var IsReattachable\ActionFactory $factory
        return $factory;
    }

    /**
     * @param null|SendTrytes\Result $executeResult
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SendTrytes\Action
     */
    public function sendTrytesAction(SendTrytes\Result $executeResult = null): SendTrytes\Action
    {
        $executeResult = $executeResult ??
            (new SendTrytes\Result())
                ->addTransaction(DummyData::getTransaction());

        $action = $this->testCase->getMockBuilder(SendTrytes\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var SendTrytes\Action $action
        return $action;
    }

    /**
     * @param SendTrytes\Action $action
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SendTrytes\ActionFactory
     */
    public function sendTrytesFactory(SendTrytes\Action $action = null): SendTrytes\ActionFactory
    {
        $action = $action ?? $this->sendTrytesAction();

        $factory = $this->testCase->getMockBuilder(SendTrytes\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var SendTrytes\ActionFactory $factory
        return $factory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|StoreAndBroadcast\Action
     */
    public function storeAndBroadcastAction(): StoreAndBroadcast\Action
    {
        $executeResult = new VoidResult();

        $action = $this->testCase->getMockBuilder(StoreAndBroadcast\Action::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $action->method('execute')->willReturn($executeResult);

        // @var StoreAndBroadcast\Action $action
        return $action;
    }

    /**
     * @param StoreAndBroadcast\Action $action
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|StoreAndBroadcast\ActionFactory
     */
    public function storeAndBroadcastFactory(StoreAndBroadcast\Action $action = null): StoreAndBroadcast\ActionFactory
    {
        $action = $action ?? $this->storeAndBroadcastAction();

        $factory = $this->testCase->getMockBuilder(StoreAndBroadcast\ActionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();
        $factory->method('factory')->willReturn($action);

        // @var StoreAndBroadcast\ActionFactory $factory
        return $factory;
    }
}
