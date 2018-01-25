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

use Psr\Container\ContainerInterface;

/**
 * Class AbstractFactory.
 *
 * Factory base class used by all factories.
 */
abstract class AbstractFactory
{
    /**
     * The Container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractFactory constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
