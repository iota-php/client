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

namespace IOTA\ClientApi\Actions\SendTransfer;

use IOTA\AbstractFactory;
use IOTA\ClientApi\Actions\GetInputs;
use IOTA\ClientApi\Actions\GetNewAddress;
use IOTA\ClientApi\Actions\SendTrytes;
use IOTA\ClientApi\FactoryInterface;
use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Cryptography\HMAC;
use IOTA\Node;
use IOTA\RemoteApi\Actions\GetBalances;

/**
 * Class ActionFactory.
 *
 * Creates a new Action instance.
 */
class ActionFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * Creates a new action instance.
     *
     * @param Node $node
     *
     * @return Action
     */
    public function factory(Node $node): Action
    {
        return new Action(
            $node,
            $this->container->get(GetNewAddress\ActionFactory::class),
            $this->container->get(GetInputs\ActionFactory::class),
            $this->container->get(GetBalances\ActionFactory::class),
            $this->container->get(SendTrytes\ActionFactory::class),
            $this->container->get(KerlFactory::class),
            $this->container->get(CurlFactory::class),
            $this->container->get(HMAC::class)
        );
    }
}
