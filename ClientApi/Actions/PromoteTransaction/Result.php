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

namespace Techworker\IOTA\ClientApi\Actions\PromoteTransaction;

use Techworker\IOTA\ClientApi\AbstractResult;

class Result extends AbstractResult
{
    /**
     * The sendTransfer result.
     *
     * @todo not sure about that, but cross extending isn't better, in this case extending from \Techworker\IOTA\ClientApi\Actions\SendTrytes\Result
     *       did that before somewhere..
     *
     * @var \Techworker\IOTA\ClientApi\Actions\SendTransfer\Result
     */
    protected $sendTransferResult;

    /**
     * @return \Techworker\IOTA\ClientApi\Actions\SendTransfer\Result
     */
    public function getSendTransferResult(): \Techworker\IOTA\ClientApi\Actions\SendTransfer\Result
    {
        return $this->sendTransferResult;
    }

    /**
     * @param \Techworker\IOTA\ClientApi\Actions\SendTransfer\Result $sendTransferResult
     *
     * @return Result
     */
    public function setSendTransferResult(\Techworker\IOTA\ClientApi\Actions\SendTransfer\Result $sendTransferResult): self
    {
        $this->sendTransferResult = $sendTransferResult;

        return $this;
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'sendTransferResult' => $this->sendTransferResult->serialize(),
        ], parent::serialize());
    }
}
