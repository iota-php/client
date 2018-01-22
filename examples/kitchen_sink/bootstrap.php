<?php

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Techworker\IOTA\Apps\Explorer;

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/functions.php';

use Techworker\IOTA\ClientApi\ClientApi;
use Techworker\IOTA\DI\IOTAContainer;
use Techworker\IOTA\IOTA;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\RemoteApi;

$nodes = [
    new Node('http://node01.iotatoken.nl:14265'),
    new Node('http://node02.iotatoken.nl:14265'),
    new Node('http://node03.iotatoken.nl:15265'),
    new Node('http://node04.iotatoken.nl:14265'),
    new Node('http://node05.iotatoken.nl:16265'),
];

$options = [
    'ccurlPath' => __DIR__.'/../../ccurl'
];

$container = new IOTAContainer($options);

return new IOTA($container->get(RemoteApi::class), $container->get(ClientApi::class), $nodes);
