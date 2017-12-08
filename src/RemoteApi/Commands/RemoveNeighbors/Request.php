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

namespace Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors;

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Util\ValidatorUtil;

/**
 * Class Action.
 *
 * Removes a list of neighbors to your node. This is only temporary, and if you
 * have your neighbors added via the command line, they will be retained after
 * you restart your node.
 *
 * @see https://iota.readme.io/docs/removeneighors
 */
class Request extends AbstractRequest
{
    /**
     * The list of neighbors to remove.
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
            'command' => 'removeNeighbors',
            'uris' => $this->neighborUris,
        ];
    }

    /**
     * Overwrites all neighbor uris.
     *
     * @param \string[] $neighborUris
     *
     * @return Request
     * @throws \InvalidArgumentException
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
     * Gets the list of neighbor uris.
     *
     * @return \string[]
     */
    public function getNeighborUris(): array
    {
        return $this->neighborUris;
    }

    /**
     * Executes the request.
     *
     * @return AbstractResponse|Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $response = new Response($this);
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    public function serialize()
    {
        return array_merge(parent::serialize(), [
            'neighborUris' => $this->neighborUris
        ]);
    }
}
