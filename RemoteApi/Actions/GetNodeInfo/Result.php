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

namespace Techworker\IOTA\RemoteApi\Actions\GetNodeInfo;

use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\Type\Milestone;

/**
 * Class Response.
 *
 * Contains information about a node.
 *
 * @see https://iota.readme.io/docs/getnodeinfo
 */
class Result extends AbstractResult
{
    /**
     * Name of the IOTA software you're currently using (IRI stands for
     * Initial Reference Implementation).
     *
     * @var string
     */
    protected $appName;

    /**
     * The version of the IOTA software you're currently running.
     *
     * @var string
     */
    protected $appVersion;

    /**
     * Available cores on your machine for JRE.
     *
     * @var int
     */
    protected $jreAvailableProcessors;

    /**
     * The amount of free memory in the Java Virtual Machine.
     *
     * @var int
     */
    protected $jreFreeMemory;

    /**
     * The total amount of memory in the Java virtual machine.
     *
     * @var int
     */
    protected $jreTotalMemory;

    /**
     * The maximum amount of memory that the Java virtual machine will attempt
     * to use.
     *
     * @var int
     */
    protected $jreMaxMemory;

    /**
     * Latest milestone that was signed off by the coordinator.
     *
     * @var Milestone
     */
    protected $latestMilestone;

    /**
     * The latest milestone which is solid and is used for sending transactions.
     * For a milestone to become solid your local node must basically approve
     * the subtangle of coordinator-approved transactions, and have a
     * consistent view of all referenced transactions.
     *
     * @var Milestone
     */
    protected $latestSolidSubtangleMilestone;

    /**
     * Number of neighbors you are directly connected with.
     *
     * @var int
     */
    protected $neighbors;

    /**
     * Packets which are currently queued up.
     *
     * @var int
     */
    protected $packetQueueSize;

    /**
     * Current time.
     *
     * @var int
     */
    protected $time;

    /**
     * Number of tips in the network.
     *
     * @var int
     */
    protected $tips;

    /**
     * Transactions to request during syncing process.
     *
     * @var int
     */
    protected $transactionsToRequest;

    /**
     * Gets the name of the IOTA software you're currently using (IRI stands
     * for Initial Reference Implementation).
     *
     * @return string
     */
    public function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * Gets the version of the IOTA software you're currently running.
     *
     * @return string
     */
    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * Gets the available cores on your machine for JRE.
     *
     * @return int
     */
    public function getJreAvailableProcessors(): int
    {
        return $this->jreAvailableProcessors;
    }

    /**
     * Gets the amount of free memory in the Java Virtual Machine.
     *
     * @return int
     */
    public function getJreFreeMemory(): int
    {
        return $this->jreFreeMemory;
    }

    /**
     * Gets the maximum amount of memory that the Java virtual machine will
     * attempt to use.
     *
     * @return int
     */
    public function getJreTotalMemory(): int
    {
        return $this->jreTotalMemory;
    }

    /**
     * Gets the total amount of memory in the Java virtual machine.
     *
     * @return int
     */
    public function getJreMaxMemory(): int
    {
        return $this->jreMaxMemory;
    }

    /**
     * Gets the latest milestone that was signed off by the coordinator.
     *
     * @return Milestone
     */
    public function getLatestMilestone(): Milestone
    {
        return $this->latestMilestone;
    }

    /**
     * Gets the latest milestone which is solid and is used for sending
     * transactions. For a milestone to become solid your local node must
     * basically approve the subtangle of coordinator-approved transactions,
     * and have a consistent view of all referenced transactions.
     *
     * @return Milestone
     */
    public function getLatestSolidSubtangleMilestone(): Milestone
    {
        return $this->latestSolidSubtangleMilestone;
    }

    /**
     * Gets the number of neighbors you are directly connected with.
     *
     * @return int
     */
    public function getNeighbors(): int
    {
        return $this->neighbors;
    }

    /**
     * Gets the packets which are currently queued up.
     *
     * @return int
     */
    public function getPacketQueueSize(): int
    {
        return $this->packetQueueSize;
    }

    /**
     * Gets the time on the server.
     *
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * Gets the number of tips in the network.
     *
     * @return int
     */
    public function getTips(): int
    {
        return $this->tips;
    }

    /**
     * Gets the transactions to request during syncing process.
     *
     * @return int
     */
    public function getTransactionsToRequest(): int
    {
        return $this->transactionsToRequest;
    }

    /**
     * Gets a value indicating whether the node is in sync.
     *
     * @return bool
     */
    public function isInSync(): bool
    {
        return $this->latestSolidSubtangleMilestone->getIndex() <
            $this->latestMilestone->getIndex();
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'appName' => $this->appName,
            'appVersion' => $this->appVersion,
            'jreAvailableProcessors' => $this->jreAvailableProcessors,
            'jreFreeMemory' => $this->jreFreeMemory,
            'jreMaxMemory' => $this->jreMaxMemory,
            'jreTotalMemory' => $this->jreTotalMemory,
            'latestMilestone' => $this->latestMilestone->serialize(),
            'latestSolidSubtangleMilestone' => $this->latestSolidSubtangleMilestone->serialize(),
            'neighbors' => $this->getNeighbors(),
            'time' => $this->getTime(),
            'tips' => $this->getTips(),
            'transactionsToRequest' => $this->transactionsToRequest,
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
        $this->checkRequiredKeys([
            'appName',
            'appVersion',
            'jreAvailableProcessors',
            'jreFreeMemory',
            'jreMaxMemory',
            'jreTotalMemory',
            'latestMilestone',
            'latestMilestoneIndex',
            'latestSolidSubtangleMilestone',
            'latestSolidSubtangleMilestoneIndex',
            'neighbors',
            'time',
            'tips',
            'transactionsToRequest',
        ]);

        // quirk in doc and implementation
        if (!isset($this->rawData['packetQueueSize']) &&
            !isset($this->rawData['packetsQueueSize'])) {
            // trigger error
            $this->checkRequiredKeys(['packetQueueSize']);
        }

        $this->appName = (string) $this->rawData['appName'];
        $this->appVersion = (string) $this->rawData['appVersion'];
        $this->jreAvailableProcessors = (int) $this->rawData['jreAvailableProcessors'];
        $this->jreFreeMemory = (int) $this->rawData['jreFreeMemory'];
        $this->jreMaxMemory = (int) $this->rawData['jreMaxMemory'];
        $this->jreTotalMemory = (int) $this->rawData['jreTotalMemory'];
        $this->latestMilestone = new Milestone(
            (string) $this->rawData['latestMilestone'],
            (int) $this->rawData['latestMilestoneIndex']
        );
        $this->latestSolidSubtangleMilestone = new Milestone(
            (string) $this->rawData['latestSolidSubtangleMilestone'],
            (int) $this->rawData['latestSolidSubtangleMilestoneIndex']
        );

        $this->neighbors = (int) $this->rawData['neighbors'];
        $this->packetQueueSize = (int) ($this->rawData['packetQueueSize'] ?? $this->rawData['packetsQueueSize']);
        $this->time = (int) $this->rawData['time'];
        $this->tips = (int) $this->rawData['tips'];
        $this->transactionsToRequest = (int) $this->rawData['transactionsToRequest'];
    }
}
