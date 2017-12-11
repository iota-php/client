<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\Tests;

use Prophecy\Prophet;
use Techworker\IOTA\DI\IOTAContainer;
use Techworker\IOTA\Node;

/**
 * Class Container
 *
 * A derived container with methods to overwrite instance creation.
 */
class Container extends IOTAContainer
{
    /**
     * Container constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'keccak384-nodejs' => 'http://127.0.0.1:8081'
        ]);
    }

    public function set(string $class, callable $callable)
    {
        $this->entries[$class] = $callable;
        return $this;
    }
}