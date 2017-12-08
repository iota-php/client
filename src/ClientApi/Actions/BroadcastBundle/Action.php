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

namespace Techworker\IOTA\ClientApi\Actions\BroadcastBundle;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\ClientApi\Actions\GetBundle;
use Techworker\IOTA\ClientApi\VoidResult;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\BroadcastTransactions;
use Techworker\IOTA\Type\TransactionHash;

/**
 * This action takes a tail transaction hash (currentIndex = 0), gets the
 * bundle associated with the transaction and then rebroadcasts all transaction
 * of the entire bundle.
 */
class Action extends AbstractAction
{
    use GetBundle\ActionTrait,
        BroadcastTransactions\RequestTrait;

    /**
     * The tail transaction hash.
     *
     * @var TransactionHash
     */
    protected $tailTransactionHash;

    /**
     * Action constructor.
     *
     * @param Node                                 $node
     * @param GetBundle\ActionFactory              $getBundleFactory
     * @param BroadcastTransactions\RequestFactory $broadcastTransactionsFactory
     */
    public function __construct(
        Node $node,
                                GetBundle\ActionFactory $getBundleFactory,
                                BroadcastTransactions\RequestFactory $broadcastTransactionsFactory
    ) {
        $this->setGetBundleFactory($getBundleFactory);
        $this->setBroadcastTransactionsFactory($broadcastTransactionsFactory);
        parent::__construct($node);
    }

    /**
     * Sets the tail transaction hash.
     *
     * @param TransactionHash $tailTransactionHash
     *
     * @return Action
     */
    public function setTailTransactionHash(TransactionHash $tailTransactionHash): self
    {
        $this->tailTransactionHash = $tailTransactionHash;

        return $this;
    }

    /**
     * Gets the tail transaction hash.
     *
     * @return TransactionHash
     */
    public function getTailTransactionHash(): TransactionHash
    {
        return $this->tailTransactionHash;
    }

    /**
     * Executes the action.
     *
     * @return AbstractResult|VoidResult
     */
    public function execute(): VoidResult
    {
        $result = new VoidResult($this);

        // fetch the bundle from the given transaction
        $getBundleResult = $this->getBundle($this->node, $this->tailTransactionHash);
        $result->addChildTrace($getBundleResult->getTrace());

        // broadcast all transactions from the bundle
        $broadcastTransactionsResponse = $this->broadcastTransactions(
            $this->node,
            array_reverse($getBundleResult->getBundle()->getTransactions())
        );
        $result->addChildTrace($broadcastTransactionsResponse->getTrace());

        $result->finish();

        return $result;
    }

    /**
     * Gets the serialized version of the action.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'tailTransactionHash' => $this->tailTransactionHash->serialize(),
        ]);
    }
}
