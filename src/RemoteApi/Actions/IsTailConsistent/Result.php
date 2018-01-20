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

namespace Techworker\IOTA\RemoteApi\Actions\IsTailConsistent;

use Techworker\IOTA\RemoteApi\AbstractResult;

/**
 * Class Response.
 *
 * TODO
 */
class Result extends AbstractResult
{
    /**
     * The consistency state.
     *
     * @var bool
     */
    protected $state;

    /**
     * Gets the conssistency state.
     *
     * @return bool
     */
    public function getState(): bool
    {
        return $this->state;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'state' => $this->state,
        ], parent::serialize());
    }

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['state']);
        $this->state = $this->rawData['state'];
    }
}
