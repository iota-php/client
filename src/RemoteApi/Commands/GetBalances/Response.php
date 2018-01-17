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

namespace Techworker\IOTA\RemoteApi\Commands\GetBalances;

use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\Type\Iota;
use Techworker\IOTA\Type\Milestone;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Response.
 *
 * The raw list of balances + milestone for the given addresses.
 *
 * @see https://iota.readme.io/docs/getbalances
 */
class Response extends AbstractResponse
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
     * @var Milestone
     */
    protected $milestone;

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
     * @return Milestone
     */
    public function getMilestone(): Milestone
    {
        return $this->milestone;
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
            'milestone' => $this->milestone->serialize(),
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
        $this->checkRequiredKeys(['milestone', 'milestoneIndex', 'balances']);

        $this->milestone = new Milestone(
            (string) $this->rawData['milestone'],
            (int) $this->rawData['milestoneIndex']
        );

        $this->balances = [];
        /** @var Request $request */
        $request = $this->request;
        // @noinspection ForeachSourceInspection
        foreach ($this->rawData['balances'] as $idx => $balance) {
            $this->balances[(string) ($request->getAddresses()[$idx])] = new Iota($balance);
        }
    }
}
