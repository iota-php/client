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

namespace Techworker\IOTA\ClientApi\Actions\GetInputs;

use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Type\Input;
use Techworker\IOTA\Type\Iota;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Replays a transfer by doing Proof of Work again.
 */
class Result extends AbstractResult
{
    /**
     * @var Input[]
     */
    protected $inputs = [];

    /**
     * @var Iota
     */
    protected $balance;

    /**
     * @return Input[]
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function addInput(Input $input)
    {
        $this->inputs[] = $input;
    }

    /**
     * @param Input[] $inputs
     */
    public function setInputs(array $inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * @return Iota
     */
    public function getBalance(): Iota
    {
        return $this->balance;
    }

    /**
     * @param Iota $balance
     */
    public function setBalance(Iota $balance)
    {
        $this->balance = $balance;
    }

    /**
     * Gets the serialized version of the result.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge([
            'inputs' => SerializeUtil::serializeArray($this->inputs),
            'balance' => $this->balance->serialize(),
        ], parent::serialize());
    }
}
