<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\RemoteApi\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Jobs\Request as JobRequest;

/**
 * Class GuzzleClient.
 *
 * A HttpClientInterface interface implementation using good old curl.
 */
class GuzzleClient implements HttpClientInterface
{
    /**
     * Executes the given command with the help of curl.
     *
     * @param AbstractRequest $request
     *
     * @return array
     */
    public function commandRequest(AbstractRequest $request): array
    {
        $client = new Client();

        $json = json_encode($request);
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => \strlen($json),
            'X-IOTA-API-Version' => $request->getNode()->getApiVersion(),
        ];

        // we have a token for the node?
        if (null !== $request->getNode()->getToken()) {
            $headers['Authorization'] = 'token '.$request->getNode()->getToken();
        }

        try {
            $response = $client->request('POST', $request->getNode()->getCommandsEndpoint(), [
                RequestOptions::HEADERS => $headers,
                RequestOptions::BODY => $json
            ]);

            return [
                'code' => $response->getStatusCode(),
                'raw' => (string) $response->getBody(),
            ];
        } catch (RequestException $rEx) {
            $response = $rEx->getResponse();
            if ($response !== null) {
                return [
                    'code' => $response->getStatusCode(),
                    'raw' => json_encode([
                        'raw' => (string)$response->getBody(),
                        'message' => $rEx->getMessage()
                    ])
                ];
            }
            return [
                'code' => -1,
                'raw' => 'An unknown error occurred, unable to fetch response body.',
            ];
        }
    }

    /**
     * @param JobRequest $jobRequest
     * @param Node $node
     * @return AbstractResponse
     */
    public function jobRequest(JobRequest $jobRequest, Node $node): AbstractResponse
    {
        $json = json_encode($jobRequest);

        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . \strlen($json),
        ];

        // we have a token for the node?
        if (null !== $node->getToken()) {
            $headers[] = 'Authorization: token '.$node->getToken();
        }

        $ch = curl_init($node->getJobsEndpoint($jobRequest->getJobId()));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseClass = $request->getResponseClass();

        return new $responseClass($status, $body, $request, $node);
    }
}
