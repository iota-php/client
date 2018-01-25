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

use Techworker\IOTA\RemoteApi\AbstractResult;

/**
 * Class Response.
 *
 * Empty response from InterruptAttachingToTangle request.
 *
 * @see https://iota.readme.io/docs/interruptattachingtotangle
 */
class Result extends AbstractResult
{
    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([], parent::serialize());
    }

    protected function mapResults(): void
    {
    }
}
