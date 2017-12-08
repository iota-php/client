<?php

namespace Techworker\IOTA\Apps\KitchenSink;

use Techworker\IOTA\SerializeInterface;

function sendJson(array $data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
}

function isAjax()
{
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}