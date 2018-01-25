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

namespace IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;
use IOTA\Type\Milestone;

/**
 * @coversNothing
 */
class MilestoneTest extends TestCase
{
    public function testValidCreation()
    {
        $ms = new Milestone(str_repeat('A', 81), 1);
        static::assertEquals(str_repeat('A', 81), (string) $ms);
        static::assertEquals(1, $ms->getIndex());
    }

    public function testInvalidCreationTooShort()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Milestone(str_repeat('A', 80), 1);
    }

    public function testInvalidCreationTooLong()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Milestone(str_repeat('A', 82), 1);
    }

    public function testSerialize()
    {
        $m = new Milestone(str_repeat('A', 81), 1);
        $s = $m->serialize();
        static::assertArrayHasKey('index', $s);
        static::assertArrayHasKey('trytes', $s);

        static::assertEquals(str_repeat('A', 81), $s['trytes']);
        static::assertEquals(1, $s['index']);
    }
}
