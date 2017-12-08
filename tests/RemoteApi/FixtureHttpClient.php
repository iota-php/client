<?php
declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;

use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
use Techworker\IOTA\RemoteApi\Jobs\Request as JobRequest;
use Techworker\IOTA\RemoteApi\Node;
use Techworker\IOTA\RemoteApi\RequestInterface;

class FixtureHttpClient implements HttpClientInterface
{
    protected $status;
    protected $body;

    public function setResponseFromFixture(int $status, array $body)
    {
        $this->status = $status;
        $this->body = $body;
    }

    public function commandRequest(RequestInterface $request, Node $node): AbstractResponse
    {
        $request->jsonSerialize();
        $responseClass = $request->getResponseClass();
        return new $responseClass($this->status, json_encode($this->body), $request, new Node());
    }

    public function jobRequest(JobRequest $jobRequest, Node $node): AbstractResponse
    {
        // TODO: Implement jobRequest() method.
    }


}