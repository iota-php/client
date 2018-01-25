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

namespace Techworker\IOTA\Tests\Util;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class SerializeUtilTest.
 *
 * @coversNothing
 */
class SerializeUtilTest extends TestCase
{
    /**
     * Gets a list of test data.
     *
     * @return array
     */
    public function testSerializeArray()
    {
        $inst1 = new class() implements SerializeInterface {
            public function serialize()
            {
                return ['A' => 'B'];
            }
        };
        $inst2 = new class() implements SerializeInterface {
            public function serialize()
            {
                return ['C' => 'D'];
            }
        };

        $s = SerializeUtil::serializeArray(['FIRST' => $inst1, 'SECOND' => $inst2]);
        static::assertArrayHasKey('FIRST', $s);
        static::assertArrayHasKey('SECOND', $s);

        static::assertEquals(['A' => 'B'], $s['FIRST']);
        static::assertEquals(['C' => 'D'], $s['SECOND']);
    }
}
