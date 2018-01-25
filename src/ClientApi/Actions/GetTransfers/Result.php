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

namespace IOTA\ClientApi\Actions\GetTransfers;

class Result extends \IOTA\ClientApi\Actions\GetBundlesFromAddresses\Result
{
    // TODO: change this..
    public function fromResult(\IOTA\ClientApi\Actions\GetBundlesFromAddresses\Result $result)
    {
        $this->bundles = $result->bundles;
    }
}
