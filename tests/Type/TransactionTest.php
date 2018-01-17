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

namespace Techworker\IOTA\Base\Types\Test;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Base\Type\Transaction;

/**
 * @coversNothing
 */
class TransactionTest extends TestCase
{
    public function testValidCreation()
    {
        $data = json_decode(file_get_contents(__DIR__.'/fixtures/TransactionTest/test1.json'), true);
        $transaction = new Transaction($data['trytes']);
        static::assertEquals((string) $transaction->getAddress(), $data['address']);
        static::assertEquals((string) $transaction->getSignatureMessageFragment(), $data['signature']);
        static::assertEquals($transaction->getValue()->getAmount(), $data['value']);
        static::assertEquals($transaction->getTrunkTransaction(), $data['trunkTransaction']);
        static::assertEquals($transaction->getBranchTransaction(), $data['branchTransaction']);
        static::assertEquals($transaction->getBundle(), $data['bundle']);
        static::assertEquals($transaction->getNonce(), $data['nonce']);
        static::assertEquals($transaction->getLastIndex(), (int) ($data['lastIndex']));
        static::assertEquals($transaction->getTimestamp(), (int) ($data['timestamp']));
        static::assertEquals($transaction->getCurrentIndex(), (int) ($data['currentIndex']));

        static::assertEquals($data['trytes'], (string) $transaction);
    }
}
