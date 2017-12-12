<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Tests\RemoteApi;

use Techworker\IOTA\Node;
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
            'raw' => $this->body
        ];
    }
}