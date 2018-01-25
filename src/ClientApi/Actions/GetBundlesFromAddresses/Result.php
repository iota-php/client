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

namespace IOTA\ClientApi\Actions\GetBundlesFromAddresses;

use IOTA\ClientApi\AbstractResult;
use IOTA\Type\Bundle;
use IOTA\Util\SerializeUtil;

class Result extends AbstractResult
{
    /**
     * The list of bundles.
     *
     * @var Bundle[]
     */
    protected $bundles = [];

    /**
     * @return Bundle[]
     */
    public function getBundles(): array
    {
        return $this->bundles;
    }

    /**
     * @param Bundle[] $bundles
     *
     * @return Result
     */
    public function setBundles(array $bundles): self
    {
        $this->bundles = $bundles;

        return $this;
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'bundles' => SerializeUtil::serializeArray($this->bundles),
        ], parent::serialize());
    }
}
