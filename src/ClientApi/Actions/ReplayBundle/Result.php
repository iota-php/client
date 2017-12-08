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

namespace Techworker\IOTA\ClientApi\Actions\ReplayBundle;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\Bundle;

class Result extends AbstractResult
{
    /**
     * The sendTrytes result.
     *
     * @todo not sure about that, but cross extending isn't better, in this case extending from \Techworker\IOTA\ClientApi\Actions\SendTrytes\Result
     *       did that before somewhere..
     * @var \Techworker\IOTA\ClientApi\Actions\SendTrytes\Result
     */
    protected $sendTrytesResult;

    /**
     * The bundle that got replayed.
     *
     * @var Bundle
     */
    protected $bundle;

    /**
     * @return \Techworker\IOTA\ClientApi\Actions\SendTrytes\Result
     */
    public function getSendTrytesResult(): \Techworker\IOTA\ClientApi\Actions\SendTrytes\Result
    {
        return $this->sendTrytesResult;
    }

    /**
     * @param \Techworker\IOTA\ClientApi\Actions\SendTrytes\Result $sendTrytesResult
     * @return Result
     */
    public function setSendTrytesResult(\Techworker\IOTA\ClientApi\Actions\SendTrytes\Result $sendTrytesResult): Result
    {
        $this->sendTrytesResult = $sendTrytesResult;
        return $this;
    }

    /**
     * @return Bundle
     */
    public function getBundle(): Bundle
    {
        return $this->bundle;
    }

    /**
     * @param Bundle $bundle
     * @return Result
     */
    public function setBundle(Bundle $bundle): Result
    {
        $this->bundle = $bundle;
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
            'sendTrytesResult' => $this->sendTrytesResult->serialize(),
            'bundle' => $this->bundle->serialize()
        ], parent::serialize());
    }
}
