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

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;

/**
 * Class Action.
 *
 * Use the api/v1/jobs/:id endpoint to get the current status of a job that has
 * been queued by the sandbox. The job status can take the following values:
 *
 *  - QUEUED   job is waiting to be executed
 *  - RUNNING  currently not in use
 *  - FAILED   job failed during execution
 *  - ABORTED  currently not in use
 *  - FINISHED job finished successfully
 *
 * @see http://dev.iota.org/sandbox/#jobs
 */
class Request extends AbstractRequest
{
    /**
     * The id of the job to request.
     *
     * @var string
     */
    private $jobId;

    /**
     * @param string $jobId
     *
     * @return Request
     */
    public function setJobId(string $jobId): self
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
    }

    /**
     * Gets the name of the class that is instantiated as a response object.
     *
     * @return string
     */
    public function getResponseClass(): string
    {
        return Response::class;
    }

    /**
     * @return AbstractResponse|Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $response = new Response();
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }
}
