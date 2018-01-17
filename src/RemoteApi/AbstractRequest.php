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

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;

/**
 * Class AbstractResponse.
 *
 * An abstract response object holding all raw data from a nodes response.
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * The http client.
     *
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * The remote node.
     *
     * @var Node
     */
    protected $node;

    /**
     * AbstractRequest constructor.
     *
     * @param HttpClientInterface $httpClient
     * @param Node                $node
     */
    public function __construct(HttpClientInterface $httpClient, Node $node)
    {
        $this->httpClient = $httpClient;
        $this->node = $node;
    }

    /**
     * Gets the node used for the request.
     *
     * @return Node
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    public function serialize(): array
    {
        return [
            'node' => $this->node->serialize(),
        ];
    }
}
