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

namespace IOTA\RemoteApi\Actions\GetBalances;

use IOTA\RemoteApi\AbstractResult;
use IOTA\Type\Iota;
use IOTA\Type\Milestone;
use IOTA\Util\SerializeUtil;

/**
 * Class Response.
 *
 * The raw list of balances + milestone for the given addresses.
 *
 * @see https://iota.readme.io/docs/getbalances
 */
class Result extends AbstractResult
{
    /**
     * The list of confirmed balances.
     *
     * @var Iota[]
     */
    protected $balances;

    /**
     * The latest confirmed milestone.
     *
     * @var Milestone[]
     */
    protected $references;

    /**
     * Gets the confirmed balances.
     *
     * @return Iota[]
     */
    public function getBalances(): array
    {
        return $this->balances;
    }

    /**
     * Gets the latest confirmed milestone.
     *
     * @return Milestone[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'balances' => SerializeUtil::serializeArray($this->balances),
            'references' => array_map(function(Milestone $ms) {
                return $ms->serialize();
            }, $this->references)
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
        $this->checkRequiredKeys(['references', 'milestoneIndex', 'balances']);

        foreach($this->rawData['references'] as $reference) {
            $this->references[] = new Milestone(
                $reference,
                (int) $this->rawData['milestoneIndex']
            );
        }

        $this->balances = [];
        /** @var Action $request */
        $request = $this->action;
        // @noinspection ForeachSourceInspection
        foreach ($this->rawData['balances'] as $idx => $balance) {
            $this->balances[(string) ($request->getAddresses()[$idx])] = new Iota($balance);
        }
    }
}
