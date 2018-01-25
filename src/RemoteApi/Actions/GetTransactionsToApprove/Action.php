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

namespace IOTA\RemoteApi\Actions\GetTransactionsToApprove;

use IOTA\ClientApi\Actions\GetTransactionObjects;
use IOTA\Node;
use IOTA\RemoteApi\AbstractAction;
use IOTA\RemoteApi\AbstractResult;
use IOTA\RemoteApi\Exception;
use IOTA\RemoteApi\NodeApiClient;
use IOTA\Type\Milestone;

/**
 * Class Action.
 *
 * Tip selection which returns trunkTransaction and branchTransaction. The input
 * value is depth, which basically determines how many bundles to go back to for
 * finding the transactions to approve. The higher your depth value, the more
 * "babysitting" you do for the network (as you have to confirm more
 * transactions).
 *
 * @see https://iota.readme.io/docs/gettransactionstoapprove
 */
class Action extends AbstractAction
{
    use GetTransactionObjects\ActionTrait;

    /**
     * Number of bundles to go back to determine the transactions for approval.
     *
     * @var int
     */
    protected $depth;

    /**
     * The milestone used for the markov monte carlo stuff.
     *
     * @var Milestone
     */
    protected $reference;

    /**
     * The number of walks used for the markov monte carlo stuff.
     *
     * @var int
     */
    protected $numWalks;

    /**
     * Request constructor.
     *
     * @param GetTransactionObjects\ActionFactory $getTransactionObjectsFactory
     * @param NodeApiClient                 $httpClient
     * @param Node                                $node
     */
    public function __construct(
        GetTransactionObjects\ActionFactory $getTransactionObjectsFactory,
        NodeApiClient $httpClient,
        Node $node
    ) {
        parent::__construct($httpClient, $node);
        $this->setGetTransactionObjectsFactory($getTransactionObjectsFactory);
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
     * Gets the depth.
     *
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param Milestone $reference
     *
     * @return Action
     */
    public function setReference(Milestone $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Gets the reference milestone.
     *
     * @return Milestone
     */
    public function getReference(): Milestone
    {
        return $this->reference;
    }

    /**
     * @param int $numWalks
     *
     * @return Action
     */
    public function setNumWalks(int $numWalks): self
    {
        $this->numWalks = $numWalks;

        return $this;
    }

    /**
     * Gets the number of walks for markov.
     *
     * @return int
     */
    public function getNumWalks(): int
    {
        return $this->numWalks;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [
            'command' => 'getTransactionsToApprove',
            'depth' => $this->depth,
        ];

        if (null !== $this->reference) {
            $data['reference'] = (string) $this->reference;
        }

        if (null !== $this->numWalks) {
            $data['numWalks'] = $this->numWalks;
        }

        return $data;
    }

    /**
     * Executes the request.
     *
     * @throws Exception
     *
     * @return AbstractResult|Result
     */
    public function execute(): Result
    {
        $response = new Result($this);
        $srvResponse = $this->nodeApiClient->send($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        $response->finish();

        return $response->throwOnError();
    }

    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'depth' => $this->depth,
            'reference' => null === $this->reference ? null : $this->reference->serialize(),
            'numWalks' => $this->numWalks,
        ]);
    }
}
