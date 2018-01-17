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

namespace Techworker\IOTA\RemoteApi\Commands\GetTips;

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;

/**
 * Class Action.
 *
 * Gets the list of tips.
 *
 * @see https://iota.readme.io/docs/gettips
 */
class Request extends AbstractRequest implements \JsonSerializable
{
    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'getTips',
        ];
    }

    /**
     * Executes the request.
     *
     * @throws Exception
     *
     * @return AbstractResponse|Response
     */
    public function execute(): Response
    {
        $response = new Response($this);
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }
}
