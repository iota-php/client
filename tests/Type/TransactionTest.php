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

namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;

use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Tests\Container;
use Techworker\IOTA\Type\Transaction;

/**
 * @coversNothing
 */
class TransactionTest extends TestCase
{
    public function testValidCreation()
    {
        $container = new Container();

        $data = json_decode(file_get_contents(__DIR__.'/fixtures/TransactionTest/test1.json'), true);
        $transaction = new Transaction($container->get(CurlFactory::class), $data['trytes']);
        static::assertEquals((string) $transaction->getAddress(), $data['address']);
        static::assertEquals((string) $transaction->getSignatureMessageFragment(), $data['signature']);
        static::assertEquals($transaction->getValue()->getAmount(), $data['value']);
        static::assertEquals((string)$transaction->getTrunkTransactionHash(), $data['trunkTransaction']);
        static::assertEquals((string)$transaction->getBranchTransactionHash(), $data['branchTransaction']);
        static::assertEquals((string)$transaction->getBundleHash(), $data['bundle']);
        // TODO: check
        //static::assertEquals((string)$transaction->getNonce(), $data['nonce']);
        static::assertEquals($transaction->getLastIndex(), (int) ($data['lastIndex']));
        static::assertEquals($transaction->getTimestamp(), (int) ($data['timestamp']));
        static::assertEquals($transaction->getCurrentIndex(), (int) ($data['currentIndex']));

        static::assertEquals($data['trytes'], (string) $transaction);
    }
}
