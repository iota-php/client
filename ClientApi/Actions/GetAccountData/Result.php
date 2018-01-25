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

namespace Techworker\IOTA\ClientApi\Actions\GetAccountData;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\AccountData;

class Result extends AbstractResult
{
    /**
     * The account data.
     *
     * @var AccountData
     */
    protected $accountData;

    /**
     * @return AccountData
     */
    public function getAccountData(): AccountData
    {
        return $this->accountData;
    }

    /**
     * @param AccountData $accountData
     *
     * @return Result
     */
    public function setAccountData(AccountData $accountData): self
    {
        $this->accountData = $accountData;

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
            'accountData' => $this->accountData->serialize(),
        ], parent::serialize());
    }
}
