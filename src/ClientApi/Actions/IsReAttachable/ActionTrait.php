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

namespace Techworker\IOTA\ClientApi\Actions\IsReAttachable;

use Techworker\IOTA\Node;

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
    private $isReAttachableFactory;

    /**
     * @param ActionFactory $isReAttachableFactory
     *
     * @return ActionTrait
     */
    protected function setIsReAttachableFactory(ActionFactory $isReAttachableFactory): self
    {
        $this->isReAttachableFactory = $isReAttachableFactory;

        return $this;
    }

    /**
     * @param Node  $node
     * @param array $addresses
     *
     * @return Result
     */
    protected function isReAttachable(
        Node $node,
                                      array $addresses
    ): Result {
        $action = $this->isReAttachableFactory->factory($node);
        $action->setAddresses($addresses);

        return $action->execute();
    }
}
