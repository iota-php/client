<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\Milestone;

class MilestoneTest extends TestCase
{
    public function testValidCreation()
    {
        $ms = new Milestone(str_repeat('A', 81), 1);
        static::assertEquals(str_repeat('A', 81), (string)$ms);
        static::assertEquals(1, $ms->getIndex());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTooShort()
    {
        new Milestone(str_repeat('A', 80), 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCreationTooLong()
    {
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
