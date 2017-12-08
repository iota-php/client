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

namespace Techworker\IOTA\ClientApi;

use Techworker\IOTA\Trace;
use Techworker\IOTA\SerializeInterface;
use Techworker\IOTA\Tests\ClientApi\Actions\AbstractActionTest;

/**
 * Class AbstractResult.
 *
 * Abstract result class for client API results.
 */
abstract class AbstractResult implements SerializeInterface
{
    /**
     * Performance measurement.
     *
     * @var Trace
     */
    protected $trace;

    /**
     * @var AbstractAction
     */
    protected $action;

    /**
     * AbstractResult constructor.
     */
    public function __construct(AbstractAction $action)
    {
        $this->trace = new Trace(\get_class($this), $action);
        $this->trace->start();
        $this->action = $action;
    }

    /**
     * Saves the duration.
     *
     * @return AbstractResult
     */
    public function finish(): self
    {
        $this->trace->stop();

        return $this;
    }

    /**
     * Serializes the timing as part of the result serialization.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'trace' => [
                $this->trace->serialize(),
            ],
        ];
    }

    /**
     * Adds the performance of a sub process.
     *
     * @param Trace $trace
     *
     * @return static
     */
    public function addChildTrace(Trace $trace)
    {
        $this->trace->addChild($trace);

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
}
