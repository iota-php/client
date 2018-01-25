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

use IOTA\RemoteApi\AbstractResult;

/**
 * Class Response.
 *
 * Contains information about the added neighbors to a node.
 *
 * @see https://iota.readme.io/docs/addneigbors
 */
class Result extends AbstractResult
{
    /**
     * The number of added neighbors.
     *
     * @var int
     */
    protected $addedNeighbors;

    /**
     * Gets the list of added neighbors.
     *
     * @return int
     */
    public function getAddedNeighbors(): int
    {
        return $this->addedNeighbors;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'addedNeighbors' => $this->addedNeighbors,
        ], parent::serialize());
    }

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['addedNeighbors']);
        $this->addedNeighbors = (int) $this->rawData['addedNeighbors'];
    }
}
