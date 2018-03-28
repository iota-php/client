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

namespace IOTA\RemoteApi;

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

/**
 * Class NodeCommandClient
 * @package IOTA\RemoteApi
 */
class NodeApiClient
{
    /**
     * The http client implementation to send sync requests.
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * The http client implementation to send async requests.
     *
     * @var HttpAsyncClient
     */
    protected $httpAsyncClient;
    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * NodeApiClient constructor.
     *
     * @param HttpClient      $httpClient
     * @param HttpAsyncClient $httpAsyncClient
     * @param MessageFactory  $messageFactory
     */
    public function __construct(
        HttpClient $httpClient,
        HttpAsyncClient $httpAsyncClient,
        MessageFactory $messageFactory
    ) {
        $this->httpClient = $httpClient;
        $this->httpAsyncClient = $httpAsyncClient;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Transform the given action to a pdr-7 request.
     *
     * @param AbstractAction $action
     * @return RequestInterface
     */
    protected function buildRequest(AbstractAction $action): RequestInterface
    {
        $body = json_encode($action);
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => \strlen($body),
            'X-IOTA-API-Version' => $action->getNode()->getApiVersion(),
        ];

        return $this->messageFactory->createRequest(
            'POST',
            $action->getNode()->getCommandsEndpoint(),
            $headers,
            $body
        );
    }

    /**
     * Sends the given action to the node and returns the result.
     *
     * @param AbstractAction $action
     * @return array
     */
    public function send(AbstractAction $action): array
    {
        $request = $this->buildRequest($action);
        $response = $this->httpClient->sendRequest($request);

        return [
            'code' => $response->getStatusCode(),
            'raw' => (string)$response->getBody(),
        ];
    }

    /**
     * Sends the given action to the node and returns a promise. You'll need
     * to deal with promise handling by yourself.
     *
     * @param AbstractAction $action
     * @return Promise
     */
    public function sendAsync(AbstractAction $action): Promise
    {
        $request = $this->buildRequest($action);

        return $this->httpAsyncClient->sendAsyncRequest($request);
    }
}
