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

namespace Techworker\IOTA\RemoteApi\Commands\GetTips;

use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\Type\Tip;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Response.
 *
 * Contains the list of tips.
 *
 * @see https://iota.readme.io/docs/gettips
 */
class Response extends AbstractResponse
{
    /**
     * The list of tips.
     *
     * @var Tip[]
     */
    protected $tips;

    /**
     * Maps the response result to the predefined props.
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function mapResults(): void
    {
        $this->checkRequiredKeys(['hashes']);

        $this->tips = [];
        // loop response and map to objects.
        /** @noinspection ForeachSourceInspection */
        foreach ($this->rawData['hashes'] as $hash) {
            $this->tips[] = new Tip($hash);
        }
    }

    /**
     * Gets the list of tips.
     *
     * @return Tip[]
     */
    public function getTips(): array
    {
        return $this->tips;
    }

    /**
     * Gets the array version of the response.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'tips' => SerializeUtil::serializeArray($this->tips)
        ], parent::serialize());
    }
}
