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

namespace IOTA\ClientApi\Actions\GetAccountData;

use IOTA\Node;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;

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
    private $getAccountDataFactory;

    /**
     * @param ActionFactory $getAccountDataFactory
     *
     * @return ActionTrait
     */
    protected function setGetAccountDataFactory(ActionFactory $getAccountDataFactory): self
    {
        $this->getAccountDataFactory = $getAccountDataFactory;

        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Node          $node
     * @param Seed          $seed
     * @param int           $startIndex
     * @param SecurityLevel $security
     *
     * @return Result
     */
    protected function getAccountData(
        Node $node,
                                      Seed $seed,
                                      int $startIndex = 0,
                                      SecurityLevel $security = null
    ): Result {
        $action = $this->getAccountDataFactory->factory($node);
        $action->setSeed($seed);
        $action->setStartIndex($startIndex);

        if (null !== $security) {
            $action->setSecurity($security);
        }

        return $action->execute();
    }
}
