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

/**
 * @coversNothing
 */
class ContainerTest extends TestCase
{
    public function testInstances()
    {
        $container = new Container();
        foreach (array_keys($container->all()) as $key) {
            try {
                $container->get($key);
                static::assertTrue(true);
            } catch (\Exception $ex) {
                static::assertTrue(false, $key);
            }
        }
    }

    public function testGetException()
    {
        $this->expectException(\Techworker\IOTA\DI\NotFoundException::class);

        $container = new Container();
        $container->get('ABC');
    }

    public function testHas()
    {
        $container = new Container();
        foreach (array_keys($container->all()) as $key) {
            try {
                static::assertTrue($container->has($key));
            } catch (\Exception $ex) {
                static::assertTrue(false, $key);
            }
        }
    }
}
