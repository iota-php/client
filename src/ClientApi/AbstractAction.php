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

namespace Techworker\IOTA\ClientApi;

use Techworker\IOTA\Node;
use Techworker\IOTA\SerializeInterface;

/**
 * Class AbstractAction.
 *
 * Abstract action for all client api actions.
 */
abstract class AbstractAction implements SerializeInterface
{
    /**
     * The node to execute the remote requests on.
     *
     * @var Node
     */
    protected $node;

    /**
     * AbstractAction constructor.
     *
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * Executes the action.
     *
     * @return mixed
     */
    abstract public function execute();

    /**
     * Returns a part of the serialization.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'node' => $this->node->serialize(),
        ];
    }
}
