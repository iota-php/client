<?php declare(strict_types=1);

namespace Techworker\IOTA\ClientApi\Address;

use Techworker\IOTA\ClientApi\Actions\ActionInterface;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\AddressUtil;

class GetNewAddressAction implements ActionInterface
{
    /**
     * The seed to derive the addresses from.
     *
     * @var Seed
     */
    private $seed;

    /**
     * The index to start the generation at.
     *
     * @var int
     */
    private $startIndex = 0;

    /**
     * The security level for the generated address.
     *
     * @var SecurityLevel
     */
    private $security;

    /**
     * A flag indicating whether to add a checksum to the address or not.
     *
     * @var bool
     */
    private $addChecksum;

    /**
     * Address utility to generate an address.
     *
     * @var AddressUtil
     */
    protected $addressUtil;

    public function __construct(
        Seed $seed,
        SecurityLevel $security,
        AddressUtil $addressUtil,
        int $startIndex = 1,
        bool $addChecksum = false
    ) {
        $this->seed = $seed;
        $this->startIndex = $startIndex;
        $this->security = $security;
        $this->addChecksum = $addChecksum;
        $this->addressUtil = $addressUtil;
    }

    public function execute(array $options = [])
    {
        if ($this->startIndex < 0) {
            throw new \InvalidArgumentException('Invalid Index option provided');
        }

        $result = new Result($this);
        $index = $this->startIndex;

        // call findTransactions with each new address to see if the address
        // was already created - if no transaction is found, return the address.
        $address = $transactions = null;
        do {
            if (isset($address, $transactions)) {
                // @var Address $address
                // @var FindTransactions\Response $transactions
                $result->addPassedAddress($address, $index - 1);
                $result->addTransactions($address, ...$transactions->getTransactionHashes());
            }

            $trace = new Trace(AddressUtil::class);
            $trace->start();

            // generate new address
            $address = $this->addressUtil->generateAddress(
                $this->seed,
                $index,
                $this->security,
                $this->addChecksum
            );
            $result->addChildTrace($trace->stop());

            // fetch remotely recorded transactions
            $transactions = $this->findTransactions($this->node, [$address]);
            $result->addChildTrace($transactions->getTrace());
            ++$index;
        } while (\count($transactions->getTransactionHashes()) > 0);

        $result->setAddress($address);

        return $result->finish();
    }
}
