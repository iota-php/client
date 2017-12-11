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

namespace Techworker\IOTA\RemoteApi\Commands\StoreTransactions;

use Techworker\IOTA\RemoteApi\AbstractResponse;

/**
 * Class Response.
 *
 * Empty response from the StoreTransactions request.
 *
 * @see https://iota.readme.io/docs/storetransactions
 */
class Response extends AbstractResponse
{
    protected function mapResults(): void
    {
    }
}
