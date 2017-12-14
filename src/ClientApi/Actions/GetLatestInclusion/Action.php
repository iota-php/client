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

namespace Techworker\IOTA\ClientApi\Actions\GetLatestInclusion;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\GetInclusionStates;
use Techworker\IOTA\RemoteApi\Commands\GetNodeInfo;
use Techworker\IOTA\Type\TransactionHash;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Gets the latest inclusion state for the given transaction hashes.
 */
class Action extends AbstractAction
{
    use GetNodeInfo\RequestTrait,
        GetInclusionStates\RequestTrait;

    /**
     * A list of transaction hashes to get the inclusion state for.
     *
     * @var TransactionHash[]
     */
    protected $transactionHashes;

    /**
     * Action constructor.
     *
     * @param Node                              $node
     * @param GetNodeInfo\RequestFactory        $getNodeInfoFactory
     * @param GetInclusionStates\RequestFactory $getInclusionStatesFactory
     */
    public function __construct(
        Node $node,
                                GetNodeInfo\RequestFactory $getNodeInfoFactory,
                                GetInclusionStates\RequestFactory $getInclusionStatesFactory
    ) {
        parent::__construct($node);

        $this->setGetNodeInfoFactory($getNodeInfoFactory);
        $this->setGetInclusionStatesFactory($getInclusionStatesFactory);
    }

    /**
     * Adds a transaction hash.
     *
     * @param TransactionHash $transactionHash
     *
     * @return Action
     */
    public function addTransactionHash(TransactionHash $transactionHash): self
    {
        $this->transactionHashes[] = $transactionHash;

        return $this;
    }

    /**
     * @param TransactionHash[] $transactionHashes
     *
     * @return Action
     */
    public function setTransactionHashes(array $transactionHashes): self
    {
        $this->transactionHashes = [];
        foreach ($transactionHashes as $transactionHash) {
            $this->addTransactionHash($transactionHash);
        }

        return $this;
    }

    /**
     * Determines the inclusion state and returns it as an array of boolean
     * states.
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $result = new Result($this);

        // fetch node info to get the milestone
        $nodeInfo = $this->getNodeInfo($this->node);
        $result->addChildTrace($nodeInfo->getTrace());

        // fetch states
        $inclusionStatesResponse = $this->getInclusionStates(
            $this->node,
            $this->transactionHashes,
            [
                $nodeInfo->getLatestSolidSubtangleMilestone(),
            ]
        );
        $result->addChildTrace($inclusionStatesResponse->getTrace());

        $states = $inclusionStatesResponse->getStates();
        foreach ($this->transactionHashes as $idx => $transactionHash) {
            $result->addState($transactionHash, $states[(string)$transactionHash]);
        }

        return $result->finish();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactionHashes' => SerializeUtil::serializeArray($this->transactionHashes)
        ]);
    }
}
