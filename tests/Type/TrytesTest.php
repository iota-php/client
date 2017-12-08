<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Base\Types\Test;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\Trits;
use Techworker\IOTA\Type\Tryte;
use Techworker\IOTA\Type\Trytes;
use Techworker\IOTA\Util\TryteUtil;

class TrytesTest extends TestCase
{
    public function testConstruct()
    {
        $trytes = new Trytes();
        static::assertEmpty((string)$trytes);

        $trytes = new Trytes('ABC');
        static::assertEquals('ABC', (string)$trytes);
    }

    public function testInvalid()
    {

    }
}
