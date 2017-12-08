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

namespace Techworker\IOTA\RemoteApi\Commands\GetTransactionsToApprove;

use Techworker\IOTA\ClientApi\Actions\GetTransactionObjects;
use Techworker\IOTA\Node;
use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\RemoteApi\HttpClient\HttpClientInterface;
use Techworker\IOTA\Type\Milestone;
use Techworker\IOTA\Util\TrytesUtil;

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
class Request extends AbstractRequest
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
     * A flag indicating whether we want to ignore transactions which are
     * considered spam.
     *
     * @var bool
     */
    protected $ignoreSpamTransactions = false;

    /**
     * Request constructor.
     *
     * @param GetTransactionObjects\ActionFactory $getTransactionObjectsFactory
     * @param HttpClientInterface                 $httpClient
     * @param Node                                $node
     */
    public function __construct(
        GetTransactionObjects\ActionFactory $getTransactionObjectsFactory,
                                HttpClientInterface $httpClient,
                                Node $node
    ) {
        parent::__construct($httpClient, $node);
        $this->setGetTransactionObjectsFactory($getTransactionObjectsFactory);
    }

    /**
     * @param int $depth
     *
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * Sets a flag indicating whether we want to ignore transactions which are
     * considered spam.
     *
     * @param bool $ignoreSpamTransactions
     *
     * @return Request
     */
    public function setIgnoreSpamTransactions(bool $ignoreSpamTransactions): self
    {
        $this->ignoreSpamTransactions = $ignoreSpamTransactions;

        return $this;
    }

    /**
     * Gets a value indicating whether to ignore spam transactions.
     *
     * @return bool
     */
    public function getIgnoreSpamTransactions(): bool
    {
        return $this->ignoreSpamTransactions;
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
     * @return AbstractResponse|Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $response = new Response($this);
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        $response->finish();
        if (!$this->ignoreSpamTransactions) {
            return $response->throwOnError();
        }

        $transactionsResponse = $this->getTransactionObjects($this->node, [
            $response->getTrunkTransactionHash(),
            $response->getBranchTransactionHash(),
        ]);

        // check if the signature contains the word "spammer" for now
        // TODO: talk to the community to find a common identification
        foreach ($transactionsResponse->getTransactions() as $transaction) {
            $signature = TrytesUtil::asciiFromTrytes($transaction->getSignatureMessageFragment());
            if (substr_count(strtolower($signature), 'spammer') > 0) {
                return $this->execute();
            }
        }

        return $response->throwOnError();
    }

    public function serialize()
    {
        return array_merge(parent::serialize(), [
            'depth' => $this->depth,
            'reference' => $this->reference->serialize(),
            'numWalks' => $this->numWalks,
            'ignoreSpamTransactions' => $this->ignoreSpamTransactions
        ]);
    }
}
