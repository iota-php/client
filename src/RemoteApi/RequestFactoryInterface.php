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

namespace Techworker\IOTA\RemoteApi;

use Techworker\IOTA\Node;

/**
 * Interface FactoryInterface
 *
 * Simple factory interface.
 */
interface RequestFactoryInterface
{
    public function factory(Node $node);
}
