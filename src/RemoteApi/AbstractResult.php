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

namespace IOTA\RemoteApi;

use IOTA\SerializeInterface;
use IOTA\Trace;

/**
 * Class AbstractResponse.
 *
 * An abstract response object holding all raw data from a nodes response.
 */
abstract class AbstractResult implements SerializeInterface
{
    /**
     * The performance item.
     *
     * @var Trace
     */
    protected $trace;

    /**
     * The HTTP response code.
     *
     * @var int
     */
    protected $code;

    /**
     * The raw response as string.
     *
     * @var string
     */
    protected $body;

    /**
     * The raw decoded response.
     *
     * @var array
     */
    protected $rawData;

    /**
     * The duration the request took (server side calculated).
     *
     * @var int
     */
    protected $duration;

    /**
     * A list of keys and values which are not mappable by a response object. So
     * either they are unexpected or left out on purpose.
     *
     * @var array
     */
    protected $unexpected;

    /**
     * The original request.
     *
     * @var ActionInterface
     */
    protected $action;

    /**
     * AbstractResponse constructor.
     *
     * @param ActionInterface $action
     */
    public function __construct(ActionInterface $action)
    {
        $this->action = $action;
        $this->unexpected = [];
        $this->trace = new Trace(\get_class($this), $this->action);
        $this->trace->start();
    }

    /**
     * Initializes the response class with the response data from the server.
     *
     * @param int    $code
     * @param string $raw
     *
     * @return AbstractResult
     */
    public function initialize(int $code, string $raw): self
    {
        $this->code = $code;
        $this->body = $raw;
        $this->rawData = [$raw];
        if (\strlen($raw) > 0 && \in_array($raw[0], ['{', '['], true)) {
            $this->rawData = json_decode($raw, true);
        }

        if (!$this->isError()) {
            $this->mapResults();
        }

        return $this;
    }

    /**
     * Gets the HTTP response code.
     *
     * @return int
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * Gets the raw response as string.
     *
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Gets the raw data decoded from json. If the response was not a json
     * string the, we will return an array with a single item which is the
     * response content.
     *
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }

    /**
     * Gets a value indicating whether the response is an error or was
     * successful.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return null !== $this->code && 200 !== $this->code;
    }

    /**
     * A small helper function that throws an exception in case the response
     * is erroneous.
     *
     * @throws Exception
     *
     * @return AbstractResult
     */
    public function throwOnError(): self
    {
        if ($this->isError()) {
            throw new Exception($this);
        }

        return $this;
    }

    /**
     * Stops the performance measurement.
     *
     * @return AbstractResult
     */
    public function finish(): self
    {
        $this->trace->stop();

        return $this;
    }

    /**
     * Gets the performance item.
     *
     * @return Trace
     */
    public function getTrace(): Trace
    {
        return $this->trace;
    }

    /**
     * Gets the serialized version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'request' => $this->action->serialize(),
            'trace' => $this->trace->serialize(),
            'serverDuration' => $this->rawData['duration'] ?? 0,
            'httpResponse' => [
                'code' => $this->code,
                'rawData' => $this->rawData,
                'unexpected' => $this->unexpected,
                'body' => $this->body,
            ],
        ];
    }

    /**
     * Maps the results of $rawData to the extended response object props.
     */
    abstract protected function mapResults(): void;

    /**
     * A simple helper function that checks whether the current raw data array
     * has all the given keys.
     *
     * @param string[]   $keys
     * @param null|array $data
     *
     * @throws \RuntimeException
     */
    protected function checkRequiredKeys(array $keys, array $data = null): void
    {
        foreach ($keys as $key) {
            if (null !== $data) {
                if (!isset($data[$key])) {
                    throw new \RuntimeException('Missing key '.$key.' in response.');
                }
            } else {
                if (!isset($this->rawData[$key])) {
                    throw new \RuntimeException('Missing key '.$key.' in response.');
                }
            }
        }
    }
}
