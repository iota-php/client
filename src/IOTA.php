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

namespace Techworker\IOTA;

use Psr\Container\ContainerInterface;
use Techworker\IOTA\ClientApi\ClientApi;
use Techworker\IOTA\DI\IOTAContainer;
use Techworker\IOTA\RemoteApi\RemoteApi;

/**
 * Class IOTA.
 *
 * Root class to get access to the libraries functionalities.
 */
class IOTA
{
    /**
     * Latest api instance for a node.
     *
     * @var RemoteApi
     */
    protected $remoteApi;

    /**
     * Client api instance.
     *
     * @var ClientApi
     */
    protected $clientApi;

    /**
     * The container that holds the factories.
     *
     * @var IOTAContainer
     */
    protected $container;

    /**
     * A list of remote nodes.
     *
     * @var Node[]
     */
    protected $nodes;

    /**
     * The last used node.
     *
     * @var Node
     */
    protected $lastUsedNode;

    /**
     * IOTA constructor.
     *
     * @param ContainerInterface $container
     * @param Node[]             $nodes
     */
    public function __construct(ContainerInterface $container, array $nodes = [])
    {
        $this->container = $container;
        $this->nodes = $nodes;
    }

    /**
     * Gets the remote api instance.
     *
     * @return RemoteApi
     */
    public function getRemoteApi(): RemoteApi
    {
        if (null === $this->remoteApi) {
            $this->remoteApi = $this->get(RemoteApi::class);
        }

        return $this->remoteApi;
    }

    /**
     * Gets the client api instance.
     *
     * @return ClientApi
     */
    public function getClientApi(): ClientApi
    {
        if (null === $this->clientApi) {
            $this->clientApi = $this->get(ClientApi::class);
        }

        return $this->clientApi;
    }

    /**
     * Gets a factory for the given container identifier.
     *
     * @param string $id
     *
     * @return mixed
     *
     * @throws
     */
    public function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * Gets a node to make a request to.
     *
     * @param mixed|null $key
     * @return Node
     * @throws Exception
     */
    public function getNode($key = null): Node
    {
        if ($key === null) {
            return $this->nodes[array_rand($this->nodes)];
        }

        if (!isset($this->nodes[$key])) {
            throw new Exception('Unable to locate node with key ' . $key);
        }


        return ($this->lastUsedNode = $this->nodes[$key]);
    }

    /**
     * Gets all the nodes.
     *
     * @return Node[]
     */
    public function getNodes() : array
    {
        return $this->nodes;
    }

    /**
     * Gets the last used node.
     *
     * @return Node
     */
    public function getLastUsedNode() : Node
    {
        return $this->lastUsedNode;
    }
}
