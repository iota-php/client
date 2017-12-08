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

namespace Techworker\IOTA\ClientApi\Actions\SendTrytes;

use Techworker\IOTA\ClientApi\AbstractAction;
use Techworker\IOTA\ClientApi\AbstractResult;
use Techworker\IOTA\ClientApi\Actions\StoreAndBroadcast;
use Techworker\IOTA\Cryptography\Hashing\CurlFactory;
use Techworker\IOTA\Exception;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\Commands\AttachToTangle;
use Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove;
use Techworker\IOTA\Type\Transaction;
use Techworker\IOTA\Type\Trytes;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Sends the given trytes (transactions) and returns the list of
 * transactions.
 */
class Action extends AbstractAction
{
    use GetTransactionsToApprove\RequestTrait,
        AttachToTangle\RequestTrait,
        StoreAndBroadcast\ActionTrait;

    /**
     * Trytes from an attach process.
     *
     * @var Trytes[]
     */
    protected $transactions;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @var int
     */
    protected $minWeightMagnitude;

    /**
     * @var bool
     */
    protected $ignoreSpamTransactions;

    /**
     * The factory to create a new curl instance.
     *
     * @var CurlFactory
     */
    protected $curlFactory;

    /**
     * Action constructor.
     * @param Node $node
     * @param GetTransactionsToApprove\RequestFactory $getTransactionsToApproveFactory
     * @param AttachToTangle\RequestFactory $attachToTangleFactory
     * @param StoreAndBroadcast\ActionFactory $storeAndBroadcastFactory
     * @param CurlFactory $curlFactory
     */
    public function __construct(
        Node $node,
        GetTransactionsToApprove\RequestFactory $getTransactionsToApproveFactory,
        AttachToTangle\RequestFactory $attachToTangleFactory,
        StoreAndBroadcast\ActionFactory $storeAndBroadcastFactory,
        CurlFactory $curlFactory
    ) {
        parent::__construct($node);
        $this->setGetTransactionsToApproveFactory($getTransactionsToApproveFactory);
        $this->setAttachToTangleFactory($attachToTangleFactory);
        $this->setStoreAndBroadcastFactory($storeAndBroadcastFactory);
        $this->curlFactory = $curlFactory;
    }

    /**
     * @param Transaction[] $transactions
     *
     * @return Action
     */
    public function setTransactions(array $transactions): self
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
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
     * @param bool $ignoreSpamTransactions
     *
     * @return Action
     */
    public function setIgnoreSpamTransactions(bool $ignoreSpamTransactions): self
    {
        $this->ignoreSpamTransactions = $ignoreSpamTransactions;

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
        // fetch transactions to approve
        $transactionsToApprove = $this->getTransactionsToApprove($this->node, $this->depth, $this->ignoreSpamTransactions);
        $result->addChildTrace($transactionsToApprove->getTrace());
        $result->setTrunkTransactionHash($transactionsToApprove->getTrunkTransaction());
        $result->setBranchTransactionHash($transactionsToApprove->getBranchTransaction());

        // attach them to the tangle
        $attachedResponse = $this->attachToTangle(
            $this->node,
            $this->transactions,
            $transactionsToApprove->getTrunkTransaction(),
            $transactionsToApprove->getBranchTransaction(),
            $this->minWeightMagnitude
        );
        $result->addChildTrace($attachedResponse->getTrace());

        // we will have to wait for the sandbox
        if ($this->node->isSandbox()) {
            $this->waitForJob($attachedResponse->getId());
        }

        // store and broadcast the transaction.
        $storeAndBroadcastResponse = $this->storeAndBroadcast(
            $this->node,
            $attachedResponse->getTransactions()
        );
        $result->addChildTrace($storeAndBroadcastResponse->getTrace());

        // collect the final transactions.
        foreach ($attachedResponse->getTransactions() as $tryte) {
            $result->addTransaction(new Transaction($this->curlFactory, (string) $tryte));
        }

        return $result->finish();
    }

    /**
     * Int sandbox mode, the process is delayed and we'll have to wait for the
     * attach job to complete.
     *
     * @param $jobId
     *
     * @throws \Exception
     */
    protected function waitForJob($jobId)
    {
        while (!($job = $this->remoteApi->getJob($jobId))->isFinished()) {
            sleep(5); //js
        }

        // TODO: better exception handling
        if ('FAILED' === $job->getStatus()) {
            throw new Exception('Action was not fulfilled by sandbox.');
        }
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'transactions' => SerializeUtil::serializeArray($this->transactions),
            'depth' => $this->depth,
            'minWeightMagnitude' => $this->minWeightMagnitude,
            'ignoreSpamTransactions' => $this->ignoreSpamTransactions
        ]);
    }
}
