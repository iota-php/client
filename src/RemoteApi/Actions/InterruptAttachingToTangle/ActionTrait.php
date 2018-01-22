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

namespace Techworker\IOTA\RemoteApi\Actions\InterruptAttachingToTangle;

use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractResult;
use Techworker\IOTA\RemoteApi\Exception;

/**
 * Trait RequestTrait.
 *
 * Wrapper function to execute the request.
 */
trait ActionTrait
{
    /**
     * The request factory.
     *
     * @var ActionFactory
     */
    protected $interruptAttachingToTangleFactory;

    /**
     * Sets the factory for the request.
     *
     * @param ActionFactory $interruptAttachingToTangleFactory
     *
     * @return ActionTrait
     */
    protected function setInterruptAttachingToTangleFactory(ActionFactory $interruptAttachingToTangleFactory): self
    {
        $this->interruptAttachingToTangleFactory = $interruptAttachingToTangleFactory;

        return $this;
    }

    /**
     * Executes the request.
     *
     * @param Node $node
     *
     * @throws Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractResult|Result
     */
    protected function interruptAttachingToTangle(Node $node): Result
    {
        $request = $this->interruptAttachingToTangleFactory->factory($node);

        return $request->execute();
    }
}
