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

namespace Techworker\IOTA\RemoteApi\Commands\GetNeighbors;

use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\Type\Neighbor;

/**
 * Class Response.
 *
 * Contains information about the neighbors of a node.
 *
 * @see https://iota.readme.io/docs/getneighborsactivity
 */
class Response extends AbstractResponse
{
    /**
     * The list of neighbors.
     *
     * @var Neighbor[]
     */
    protected $neighbors;

    /**
     * Gets the list of neighbors.
     *
     * @return Neighbor[]
     */
    public function getNeighbors(): array
    {
        return $this->neighbors;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'neighbors' => $this->neighbors,
        ], parent::serialize());
    }

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['neighbors']);

        $this->neighbors = [];
        // loop response and map to objects.
        // @noinspection ForeachSourceInspection
        foreach ($this->rawData['neighbors'] as $neighbor) {
            $this->checkRequiredKeys([
                'address', 'numberOfAllTransactions',
                'numberOfInvalidTransactions', 'numberOfNewTransactions',
            ], $neighbor);

            $this->neighbors[] = new Neighbor(
                (string) $neighbor['address'],
                (int) $neighbor['numberOfAllTransactions'],
                (int) $neighbor['numberOfInvalidTransactions'],
                (int) $neighbor['numberOfNewTransactions']
            );
        }
    }
}
