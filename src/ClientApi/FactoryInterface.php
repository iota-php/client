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

use Techworker\IOTA\Node;

/**
 * Interface FactoryInterface.
 *
 * Factory interface for the ClientApi namespace.
 */
interface FactoryInterface
{
    /**
     * Creates a new instance of x.
     *
     * @param Node $node
     *
     * @return mixed
     */
    public function factory(Node $node);
}
