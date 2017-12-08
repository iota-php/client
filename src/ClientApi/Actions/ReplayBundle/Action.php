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

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\ClientApi\Actions\GetBundle;
use Techworker\IOTA\ClientApi\Actions\SendTrytes;
use Techworker\IOTA\Node;
use Techworker\IOTA\Type\TransactionHash;

/**
 * Replays a transfer by doing Proof of Work again.
 */
class Action extends AbstractAction
{
    use GetBundle\ActionTrait,
        SendTrytes\ActionTrait;

    /**
     * Tail transaction hash.
     *
     * @var TransactionHash
     */
    protected $tailTransactionHash;

    /**
     * The depth to replay.
     *
     * @var int
     */
    protected $depth;

    /**
     * The weight magnitude.
     *
     * @var int
     */
    protected $minWeightMagnitude;

    /**
     * Action constructor.
     *
     * @param Node $node
     * @param GetBundle\ActionFactory $getBundleFactory
     * @param SendTrytes\ActionFactory $sendTrytesFactory
     */
    public function __construct(
        Node $node,
        GetBundle\ActionFactory $getBundleFactory,
        SendTrytes\ActionFactory $sendTrytesFactory
    ) {
        parent::__construct($node);
        $this->setGetBundleFactory($getBundleFactory);
        $this->setSendTrytesFactory($sendTrytesFactory);
    }

    /**
     * Sets the hash of the tail transaction.
     *
     * @param TransactionHash $tailTransactionHash
     * @return Action
     */
    public function setTailTransactionHash(TransactionHash $tailTransactionHash): Action
    {
        $this->tailTransactionHash = $tailTransactionHash;
        return $this;
    }

    /**
     * Sets the depth.
     *
     * @param int $depth
     *
     * @return Action
     */
    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Sets the min weight magnitude.
     *
     * @param int $minWeightMagnitude
     *
     * @return Action
     */
    public function setMinWeightMagnitude(int $minWeightMagnitude): self
    {
        $this->minWeightMagnitude = $minWeightMagnitude;

        return $this;
    }

    /**
     * Executes the action.
     *
     * @return Result|AbstractResult
     */
    public function execute(): Result
    {
        $result = new Result($this);

        $bundleResult = $this->getBundle($this->node, $this->tailTransactionHash);
        $result->addChildTrace($bundleResult->getTrace());
        $result->setBundle($bundleResult->getBundle());

        $sendTrytesResult = $this->sendTrytes(
            $this->node,
            array_reverse($bundleResult->getBundle()->getTransactions()),
            $this->depth,
            $this->minWeightMagnitude
        );

        $result->setSendTrytesResult($sendTrytesResult);
        $result->addChildTrace($sendTrytesResult->getTrace());
        $result->finish();

        return $result;
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'tailTransactionHash' => $this->tailTransactionHash->serialize(),
            'depth' => $this->depth,
            'minWeightMagnitude' => $this->minWeightMagnitude
        ]);
    }
}
