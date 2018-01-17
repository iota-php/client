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

namespace Techworker\IOTA\RemoteApi\Commands\GetInclusionStates;

use Techworker\IOTA\RemoteApi\AbstractResponse;

/**
 * Class Response.
 *
 * The inclusion states for the given transactions.
 *
 * @see https://iota.readme.io/docs/getinclusionstates
 */
class Response extends AbstractResponse
{
    /**
     * The list of states.
     *
     * @var bool[]
     */
    protected $states;

    /**
     * Gets the states.
     *
     * @return bool[]
     */
    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'states' => $this->states,
        ], parent::serialize());
    }

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['states']);

        $this->states = [];
        /** @var Request $request */
        $request = $this->request;
        // @noinspection ForeachSourceInspection
        foreach ($this->rawData['states'] as $idx => $state) {
            $this->states[(string) $request->getTransactionHashes()[$idx]] = (bool) $state;
        }
    }
}
