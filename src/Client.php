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

namespace IOTA;

use IOTA\ClientApi\ClientApi;
use IOTA\RemoteApi\RemoteApi;

/**
 * Class Client.
 *
 * Root class to get access to the libraries functionalities.
 */
class Client
{
    /**
     * Latest api instance for a node.
     *
     * @var RemoteApi
     */
    private $remoteApi;

    /**
     * Client api instance.
     *
     * @var ClientApi
     */
    private $clientApi;

    /**
     * A list of remote nodes.
     *
     * @var Node[]
     */
    private $nodes;

    /**
     * The last used node.
     *
     * @var Node
     */
    private $lastUsedNode;

    /**
     * @param RemoteApi $remoteApi
     * @param ClientApi $clientApi
     * @param Node[]    $nodes
     */
    public function __construct(RemoteApi $remoteApi, ClientApi $clientApi, array $nodes = [])
    {
        $this->remoteApi = $remoteApi;
        $this->clientApi = $clientApi;
        $this->nodes = $nodes;
    }

    /**
     * Gets the remote API implementation.
     *
     * @return RemoteApi
     */
    public function getRemoteApi(): RemoteApi
    {
        return $this->remoteApi;
    }

    /**
     * Gets the client API implementation.
     *
     * @return ClientApi
     */
    public function getClientApi(): ClientApi
    {
        return $this->clientApi;
    }

    /**
     * Gets a node to make a request to.
     *
     * @param null|mixed $key
     *
     * @throws Exception
     *
     * @return Node
     */
    public function getNode($key = null): Node
    {
        if (null === $key) {
            $key = \array_rand($this->nodes);
        }

        if (!isset($this->nodes[$key])) {
            throw new Exception('Unable to locate node with key '.$key);
        }

        return $this->lastUsedNode = $this->nodes[$key];
    }

    /**
     * Gets all the nodes.
     *
     * @return Node[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * Gets the last used node.
     *
     * @return Node
     */
    public function getLastUsedNode(): Node
    {
        return $this->lastUsedNode;
    }
}
