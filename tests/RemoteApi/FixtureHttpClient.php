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

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;

class FixtureHttpClient implements HttpClientInterface
{
    protected $status;
    protected $body;

    public function setResponseFromFixture(int $status, string $body)
    {
        $this->status = $status;
        $this->body = $body;
    }

    public function commandRequest(AbstractRequest $request): array
    {
        return [
            'code' => $this->status,
            'raw' => $this->body,
        ];
    }
}
