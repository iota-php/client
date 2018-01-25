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

namespace IOTA\ClientApi\Actions\GetBundle;

use IOTA\ClientApi\AbstractResult;
use IOTA\Type\Bundle;

/**
 * Class Result.
 */
class Result extends AbstractResult
{
    /**
     * @var Bundle
     */
    protected $bundle;

    /**
     * Gets the bundle.
     *
     * @return Bundle
     */
    public function getBundle(): Bundle
    {
        return $this->bundle;
    }

    /**
     * Sets the bundle.
     *
     * @param Bundle $bundle
     *
     * @return Result
     */
    public function setBundle(Bundle $bundle): self
    {
        $this->bundle = $bundle;

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
            'bundle' => $this->bundle->serialize(),
        ], parent::serialize());
    }
}
