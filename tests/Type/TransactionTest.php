<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Base\Types\Test;

use PHPUnit\Framework\TestCase;
use Prophecy\Exception\InvalidArgumentException;
use Techworker\IOTA\Base\Type\Milestone;
use Techworker\IOTA\Base\Type\Transaction;
use Techworker\IOTA\Base\Type\Trit;
use Techworker\IOTA\Base\Type\Tryte;

class TransactionTest extends TestCase
{
    public function testValidCreation()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/fixtures/TransactionTest/test1.json'), true);
        $transaction = new Transaction($data['trytes']);
        static::assertEquals((string)$transaction->getAddress(), $data['address']);
        static::assertEquals((string)$transaction->getSignatureMessageFragment(), $data['signature']);
        static::assertEquals($transaction->getValue()->getAmount(), $data['value']);
        static::assertEquals($transaction->getTrunkTransaction(), $data['trunkTransaction']);
        static::assertEquals($transaction->getBranchTransaction(), $data['branchTransaction']);
        static::assertEquals($transaction->getBundle(), $data['bundle']);
        static::assertEquals($transaction->getNonce(), $data['nonce']);
        static::assertEquals($transaction->getLastIndex(), intval($data['lastIndex']));
        static::assertEquals($transaction->getTimestamp(), intval($data['timestamp']));
        static::assertEquals($transaction->getCurrentIndex(), intval($data['currentIndex']));

        static::assertEquals($data['trytes'], (string)$transaction);
    }

}
