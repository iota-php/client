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

namespace Techworker\IOTA\ClientApi\Actions\IsReAttachable;

use Techworker\IOTA\ClientApi\AbstractResult;

class Result extends AbstractResult
{
    /**
     * The re-attachable states.
     *
     * @var bool[]
     */
    protected $states;

    /**
     * @return \bool[]
     */
    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * @param \bool[] $states
     *
     * @return Result
     */
    public function setStates(array $states): self
    {
        $this->states = $states;

        return $this;
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'states' => $this->states,
        ], parent::serialize());
    }
}
