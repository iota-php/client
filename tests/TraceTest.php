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
use IOTA\SerializeInterface;
use IOTA\Trace;

/**
 * Class TraceTest.
 *
 * @coversNothing
 */
class TraceTest extends TestCase
{
    public function testTraceSimple()
    {
        $trace = new Trace('my');
        $trace->start();
        usleep(100);
        $trace->stop();

        $s = $trace->serialize();
        static::assertNull($trace->getParent());
        static::assertGreaterThan(0, $s['duration']);
        static::assertArrayNotHasKey('root', $s);
        static::assertArrayNotHasKey('children', $s);
    }

    public function testTraceExtended()
    {
        $parent = new class() implements SerializeInterface {
            public function serialize()
            {
                return [];
            }
        };

        $trace = new Trace('my', $parent);

        $trace->start();
        usleep(100);
        $child1 = new Trace('sub1');
        $child2 = new Trace('sub2');
        $trace->addChild($child1);
        $trace->addChild($child2);
        $trace->stop();

        $s = $trace->serialize();
        static::assertEquals($trace, $child1->getParent());
        static::assertEquals($trace, $child2->getParent());
        static::assertArrayHasKey('root', $s);
        static::assertArrayHasKey('children', $s);
        static::assertCount(2, $s['children']);
    }
}
