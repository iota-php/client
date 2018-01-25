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

namespace Techworker\IOTA;

/**
 * Class Trace.
 *
 * Small helper class to measure performance and see a log of all performed
 * actions / commands.
 */
class Trace implements SerializeInterface
{
    /**
     * @var Trace
     */
    protected $parent;

    /**
     * The time the performance calculation starts.
     *
     * @var float
     */
    protected $timeStart;

    /**
     * The time the performance calculation ends.
     *
     * @var float
     */
    protected $timeEnd;

    /**
     * The difference between start and end.
     *
     * @var float
     */
    protected $duration;

    /**
     * The children performance items.
     *
     * @var Trace[]
     */
    protected $children;

    /**
     * The performance identifier.
     *
     * @var string
     */
    protected $ident;

    /**
     * @var SerializeInterface
     */
    protected $root;

    /**
     * Trace constructor.
     *
     * @param string                  $ident
     * @param null|SerializeInterface $root
     */
    public function __construct(string $ident, SerializeInterface $root = null)
    {
        $this->ident = $ident;
        $this->root = $root;
        $this->children = [];
    }

    /**
     * Starts the performance timer.
     *
     * @return Trace
     */
    public function start(): self
    {
        $this->timeStart = microtime(true);

        return $this;
    }

    /**
     * Stops the performance measurement.
     *
     * @return Trace
     */
    public function stop(): self
    {
        $this->timeEnd = microtime(true);
        $this->duration = $this->timeEnd - $this->timeStart;

        return $this;
    }

    /**
     * Adds a child performance measurement.
     *
     * @param Trace $trace
     *
     * @return Trace
     */
    public function addChild(self $trace): self
    {
        $this->children[] = $trace;
        $trace->parent = $this;

        return $this;
    }

    /**
     * Gets the parent performance instance.
     *
     * @return Trace
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * Gets the serialized version.
     *
     * @return array
     */
    public function serialize(): array
    {
        $data = [
            'ident' => $this->ident,
            'duration' => round($this->duration, 4),
        ];
        if (null !== $this->root) {
            $data['root'] = $this->root->serialize();
        }

        if (\count($this->children) > 0) {
            $data['children'] = [];
        }

        foreach ($this->children as $childTrace) {
            $data['children'][] = $childTrace->serialize();
        }

        return $data;
    }
}
