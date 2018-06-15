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
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Type\AccountData;
use IOTA\Type\Address;
use IOTA\Type\Bundle;
use IOTA\Type\BundleHash;
use IOTA\Type\Input;
use IOTA\Type\Iota;
use IOTA\Type\SecurityLevel;

class AccountDataTest extends TestCase
{
    public function testConstruct()
    {
        $acc = new AccountData();
        static::assertEquals(Iota::ZERO()->getAmount(), $acc->getBalance()->getAmount());
        static::assertCount(0, $acc->getInputs());
        static::assertCount(0, $acc->getAddresses());
        static::assertCount(0, $acc->getBundles());
    }

    public function testAddresses()
    {
        $acc = new AccountData();
        $acc->addAddress(new Address());
        static::assertCount(1, $acc->getAddresses());
        $acc->setAddresses([]);
        static::assertCount(0, $acc->getAddresses());
        $acc->setAddresses([new Address(), new Address()]);
        static::assertCount(2, $acc->getAddresses());
        $acc->setAddresses([new Address()]);
        static::assertCount(1, $acc->getAddresses());
    }

    public function testBundles()
    {
        $container = new Container();
        $bundle = new Bundle(
            $container->get(KerlFactory::class),
            $container->get(CurlFactory::class),
            new BundleHash(str_repeat('A', 81))
        );
        $acc = new AccountData();
        $acc->addBundle($bundle);
        static::assertCount(1, $acc->getBundles());
        $acc->setBundles([]);
        static::assertCount(0, $acc->getBundles());
        $acc->setBundles([$bundle, $bundle]);
        static::assertCount(2, $acc->getBundles());
        $acc->setBundles([$bundle]);
        static::assertCount(1, $acc->getBundles());
    }

    public function testInputs()
    {
        $input = new Input(new Address(), Iota::ZERO(), 1, SecurityLevel::LEVEL_1());
        $acc = new AccountData();
        $acc->addInput($input);
        static::assertCount(1, $acc->getInputs());
        $acc->setInputs([]);
        static::assertCount(0, $acc->getInputs());
        $acc->setInputs([$input, $input]);
        static::assertCount(2, $acc->getInputs());
        $acc->setInputs([$input]);
        static::assertCount(1, $acc->getInputs());
    }

    public function testLastUnusedAddress()
    {
        $add = new Address();
        $acc = new AccountData();
        $acc->setLatestUnusedAddress($add);
        static::assertEquals($add, $acc->getLatestUnusedAddress());
    }

    public function testBalance()
    {
        $acc = new AccountData();
        $acc->setBalance(new Iota(10));
        static::assertEquals('10', $acc->getBalance()->getAmount());
    }

    public function testSerialize()
    {
        $addr = new Address(str_repeat('A', 81));
        $input = new Input(new Address(), Iota::ZERO(), 1, SecurityLevel::LEVEL_1());
        $container = new Container();
        $bundle = new Bundle(
            $container->get(KerlFactory::class),
            $container->get(CurlFactory::class),
            new BundleHash(str_repeat('A', 81))
        );
        $acc = new AccountData();
        $acc->setBalance(new Iota(10));
        $acc->setLatestUnusedAddress($addr);
        $acc->setInputs([$input, $input]);
        $acc->setBundles([$bundle, $bundle, $bundle]);
        $acc->setAddresses([$addr, $addr, $addr, $addr]);

        $s = $acc->serialize();
        static::assertArrayHasKey('balance', $s);
        static::assertArrayHasKey('inputs', $s);
        static::assertArrayHasKey('addresses', $s);
        static::assertArrayHasKey('bundles', $s);
        static::assertArrayHasKey('latestUnusedAddress', $s);

        static::assertCount(4, $s['addresses']);
        static::assertCount(3, $s['bundles']);
        static::assertCount(2, $s['inputs']);
    }
}
