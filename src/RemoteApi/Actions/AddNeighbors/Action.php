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

namespace IOTA\RemoteApi\Actions\AddNeighbors;

use IOTA\RemoteApi\AbstractAction;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\Util\ValidatorUtil;

/**
 * Class Request.
 *
 * Adds one or more neighbors to the given node. This is only temporary for the
 * node, they will be removed from the set of neighbors after you relaunch IRI.
 *
 * Use the `--neighbors` parameter in your node configuration to permanently add
 * neighbors.
 *
 * Most nodes obviously deny access to that endpoint.
 *
 * @see https://github.com/iotaledger/iri
 * @see https://iota.readme.io/docs/addneigbors
 */
class Action extends AbstractAction
{
    /**
     * The list of neighbors to add.
     *
     * @var string[]
     */
    protected $neighborUris;

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'command' => 'addNeighbors',
            'uris' => $this->neighborUris,
        ];
    }

    /**
     * Overwrites all neighbor uris.
     *
     * @param string[] $neighborUris
     *
     * @throws \InvalidArgumentException
     *
     * @return Action
     */
    public function setNeighborUris(array $neighborUris): self
    {
        $this->neighborUris = [];
        foreach ($neighborUris as $neighborUri) {
            $this->addNeighborUri($neighborUri);
        }

        return $this;
    }

    /**
     * Adds a single neighbor uri.
     *
     * @param string $neighborUri
     *
     * @throws \InvalidArgumentException
     */
    public function addNeighborUri(string $neighborUri)
    {
        if (false === ValidatorUtil::isNeighborUri($neighborUri)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid neighbor uri %s given.',
                    $neighborUri
                )
            );
        }

        $this->neighborUris[] = $neighborUri;
    }

    /**
     * Gets the neighbor uris.
     *
     * @return string[]
     */
    public function getNeighborUris(): array
    {
        return $this->neighborUris;
    }

    /**
     * Executes the request.
     *
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $response = new Result($this);
        $srvResponse = $this->nodeApiClient->send($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    /**
     * Gets the array representation of the request.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'neighborUris' => $this->neighborUris,
        ]);
    }
}
