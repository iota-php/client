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

use Techworker\IOTA\Node;
use Techworker\IOTA\Type\Iota;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;

/**
 * Replays a transfer by doing Proof of Work again.
 */
trait ActionTrait
{
    /**
     * The action factory.
     *
     * @var ActionFactory
     */
    private $getInputsFactory;

    /**
     * @param ActionFactory $getInputsFactory
     *
     * @return ActionTrait
     */
    protected function setGetInputsFactory(ActionFactory $getInputsFactory): self
    {
        $this->getInputsFactory = $getInputsFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @param Node $node
     * @param Seed $seed
     * @param int $startIndex
     * @param int $endIndex
     * @param Iota|null $threshold
     * @param SecurityLevel|null $security
     * @return Result
     */
    protected function getInputs(
        Node $node,
                                 Seed $seed,
                                 int $startIndex = 0,
                                 int $endIndex = -1,
                                 Iota $threshold = null,
                                 SecurityLevel $security = null
    ): Result {
        $action = $this->getInputsFactory->factory($node);
        $action->setSeed($seed);
        $action->setStartIndex($startIndex);
        $action->setEndIndex($endIndex);

        if (null !== $threshold) {
            $action->setThreshold($threshold);
        }
        if (null !== $security) {
            $action->setSecurity($security);
        }

        return $action->execute();
    }
}
