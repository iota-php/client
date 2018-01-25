<?php

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace IOTA\Tests\ClientApi\Actions;

use PHPUnit\Framework\TestCase;
use IOTA\Client;
use IOTA\Node;
use IOTA\Tests\ClientApiMocks;
use IOTA\Tests\Container;
use IOTA\Tests\DummyData;
use IOTA\Tests\RemoteApiMocks;

abstract class AbstractActionTest extends TestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Client
     */
    protected $iota;

    /**
     * @var DummyData
     */
    protected $dummyTypeData;

    /**
     * @var ClientApiMocks
     */
    protected $caMocks;

    /**
     * @var RemoteApiMocks
     */
    protected $raMocks;

    /**
     * AbstractActionTest constructor.
     */
    public function __construct()
    {
        $this->caMocks = new ClientApiMocks($this);
        $this->raMocks = new RemoteApiMocks($this);

        parent::__construct();
    }

    /**
     * Re-Initialitzes various stuff.
     */
    public function setUp()
    {
        DummyData::init();
        $this->container = new Container();
        $this->iota = new Client($this->container, [DummyData::getNode()]);
    }

    /**
     * Asserts whether the serialized version of the given action contains
     * a node.
     */
    protected function assertSerializedActionHasNode(array $serializedAction, Node $node)
    {
        static::assertEquals($node->jsonSerialize(), $serializedAction['node']);
    }
}
