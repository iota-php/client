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

namespace Techworker\IOTA\RemoteApi;

use Techworker\IOTA\SerializeInterface;

/**
 * Interface RequestInterface.
 *
 * The interface to implement a command that can be executed against a nodes
 * command api.
 */
interface RequestInterface extends SerializeInterface, \JsonSerializable
{
    /**
     * Executes the request.
     *
     * @return mixed
     */
    public function execute();
}
