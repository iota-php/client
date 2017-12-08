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

namespace Techworker\IOTA\ClientApi\Actions\GetBundle;

use Techworker\IOTA\ClientApi\Actions\GetBundle;
use Techworker\IOTA\Node;
use Techworker\IOTA\Type\TransactionHash;

/**
 * Replays a transfer by doing Proof of Work again.
 */
trait ActionTrait
{
    /**
     * @var GetBundle\ActionFactory
     */
    private $getBundleFactory;

    /**
     * @param ActionFactory $getBundleFactory
     *
     * @return ActionTrait
     */
    protected function setGetBundleFactory(ActionFactory $getBundleFactory): self
    {
        $this->getBundleFactory = $getBundleFactory;

        return $this;
    }

    /**
     * @param Node            $node
     * @param TransactionHash $transactionHash
     *
     * @return Result
     */
    protected function getBundle(Node $node, TransactionHash $transactionHash): Result
    {
        $action = $this->getBundleFactory->factory($node);
        $action->setTransactionHash($transactionHash);

        return $action->execute();
    }
}
