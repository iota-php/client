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

namespace Techworker\IOTA\RemoteApi\HttpClient;

use Techworker\IOTA\RemoteApi\AbstractRequest;

/**
 * Interface HttpClientInterface.
 *
 * Interface that defines the implementation of a http client that can talk
 * to the api.
 */
interface HttpClientInterface
{
    /**
     * Executes the given request on the given node and returns the response.
     *
     * @param AbstractRequest $request
     *
     * @return array
     */
    public function commandRequest(AbstractRequest $request): array;
}
