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

namespace Techworker\IOTA\Tests\ClientApi\Actions;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\ClientApi\ClientApi;
use Techworker\IOTA\IOTA;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\RemoteApi;
use Techworker\IOTA\Tests\ClientApiMocks;
use Techworker\IOTA\Tests\Container;
use Techworker\IOTA\Tests\DummyData;
use Techworker\IOTA\Tests\RemoteApiMocks;

abstract class AbstractActionTest extends TestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var IOTA
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
        $this->iota = new IOTA(
            $this->prophesize(RemoteApi::class)->reveal(),
            $this->prophesize(ClientApi::class)->reveal(),
            [DummyData::getNode()]
        );
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
