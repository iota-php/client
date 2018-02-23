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

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Mock\Client;
use IOTA\DI\IOTAContainer;

/**
 * Class Container.
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
            'ccurlPath' => __DIR__ . '/ccurl',
        ]);

        $this->set(HttpClient::class, function() {
            return new Client();
        });
        $this->set(HttpAsyncClient::class, function() {
            return new Client();
        });
    }

    public function set(string $class, callable $callable)
    {
        $this->entries[$class] = $callable;

        return $this;
    }

    public function all()
    {
        return $this->entries;
    }
}
