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

namespace Techworker\IOTA\RemoteApi\Commands\RemoveNeighbors;

use Techworker\IOTA\RemoteApi\AbstractResponse;

/**
 * Class Response.
 *
 * Contains information about the removed neighbors.
 *
 * @see https://iota.readme.io/docs/removeneighors
 */
class Response extends AbstractResponse
{
    /**
     * The number of added neighbors.
     *
     * @var int
     */
    protected $removedNeighbors;

    /**
     * Gets the list of removed neighbors.
     *
     * @return int
     */
    public function getRemovedNeighbors(): int
    {
        return $this->removedNeighbors;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'removedNeighbors' => $this->removedNeighbors,
        ], parent::serialize());
    }

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['removedNeighbors']);
        $this->removedNeighbors = (int) $this->rawData['removedNeighbors'];
    }
}
