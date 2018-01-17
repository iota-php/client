<?php

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Techworker\IOTA\Apps\KitchenSink;

function sendJson(array $data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
}

function isAjax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        'xmlhttprequest' === strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
}
