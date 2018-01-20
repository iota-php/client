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

/**
 * Class AbstractResponse.
 *
 * An abstract response object holding all raw data from a nodes response.
 */
abstract class AbstractAction implements ActionInterface
{
    /**
     * The http client.
     *
     * @var NodeApiClient
     */
    protected $nodeApiClient;

    /**
     * The remote node.
     *
     * @var Node
     */
    protected $node;

    /**
     * AbstractRequest constructor.
     *
     * @param NodeApiClient $httpClient
     * @param Node                $node
     */
    public function __construct(NodeApiClient $httpClient, Node $node)
    {
        $this->nodeApiClient = $httpClient;
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
