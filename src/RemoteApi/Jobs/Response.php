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

namespace Techworker\IOTA\RemoteApi\Jobs;

use Techworker\IOTA\RemoteApi\AbstractResponse;

/**
 * Class Response.
 *
 * Contains information about a job.
 *
 * @see http://dev.iota.org/sandbox/#jobs
 */
class Response extends AbstractResponse
{
    /**
     * The id of the job.
     *
     * @var string
     */
    private $id;

    /**
     * The status of the job.
     *
     * @var string
     */
    private $status;

    /**
     * The timestamp when the job was created.
     *
     * @var int
     */
    private $createdAt;

    /**
     * The timestamp when the job was started.
     *
     * @var int|null
     */
    private $startedAt;

    /**
     * The timestamp when the job was finished.
     *
     * @var int|null
     */
    private $finishedAt;

    /**
     * The command that was executed with the job.
     *
     * @var int
     */
    private $command;

    /**
     * The command data.
     *
     * @var array
     */
    private $requestData;

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     */
    protected function mapResults(): void
    {
        $keys = ['id', 'status', 'createdAt', 'startedAt', 'finishedAt', 'command'];
        $this->checkRequiredKeys($keys);
        $this->id = $this->rawData['id'];
        $this->status = $this->rawData['status'];
        $this->createdAt = (int) $this->rawData['createdAt'];
        $this->startedAt = null !== $this->rawData['startedAt'] ? (int) $this->rawData['startedAt'] : $this->rawData['startedAt'];
        $this->finishedAt = null !== $this->rawData['finishedAt'] ? (int) $this->rawData['finishedAt'] : $this->rawData['finishedAt'];
        $this->command = $this->rawData['command'];

        foreach ($this->rawData as $k => $r) {
            if (!\in_array($k, $keys, true)) {
                $this->requestData = $this->rawData['command'];
            }
        }
    }

    /**
     * Gets the id of the job.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the status of the job.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Gets a value indicating whether the job is done.
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return 'FAILED' === $this->status || 'FINISHED' === $this->status;
    }

    /**
     * Gets the creation date of the job.
     *
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * Gets the started date of the job.
     *
     * @return int|null
     */
    public function getStartedAt(): ?int
    {
        return $this->startedAt;
    }

    /**
     * Gets the finished date of the job.
     *
     * @return int|null
     */
    public function getFinishedAt(): ?int
    {
        return $this->finishedAt;
    }

    /**
     * Gets the executed command name.
     *
     * @return int
     */
    public function getCommand(): int
    {
        return $this->command;
    }

    /**
     * Gets the data of the request.
     *
     * @return array
     */
    public function getRequestData(): array
    {
        return $this->requestData;
    }
}
