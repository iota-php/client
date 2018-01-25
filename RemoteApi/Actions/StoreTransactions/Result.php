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

namespace Techworker\IOTA\RemoteApi\Actions\StoreTransactions;

use Techworker\IOTA\RemoteApi\AbstractResult;

/**
 * Class Response.
 *
 * Empty response from the StoreTransactions request.
 *
 * @see https://iota.readme.io/docs/storetransactions
 */
class Result extends AbstractResult
{
    protected function mapResults(): void
    {
    }
}
