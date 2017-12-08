<?php

namespace Techworker\IOTA\Apps\Explorer;

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
require_once __DIR__ . '/../../vendor/autoload.php';

use Techworker\IOTA\DI\IOTAContainer;
use Techworker\IOTA\IOTA;
use Techworker\IOTA\Node;

$nodes = [
    new Node('http://service.iotasupport.com:14265')
];

$options = [
    'keccak384-nodejs' => 'http://127.0.0.1:8081',
    'ccurlPath' => __DIR__ . '/../../ccurl'
];

return new IOTA(new IOTAContainer($options), $nodes);
