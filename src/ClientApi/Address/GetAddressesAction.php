<?php declare(strict_types=1);

namespace Techworker\IOTA\ClientApi\Address;

use Techworker\IOTA\ClientApi\Actions\ActionInterface;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\AddressUtil;

class GetAddressesAction implements ActionInterface
{
    /**
     * The seed.
     *
     * @var Seed
     */
    protected $seed;

    /**
     * The start index.
     *
     * @var int
     */
    protected $startIndex = 0;

    /**
     * The level of security.
     *
     * @var SecurityLevel
     */
    protected $security;

    /**
     * A value indicating whether to add a checksum to the addresses.
     *
     * @var bool
     */
    protected $addChecksum = false;

    /**
     * Address utility.
     *
     * @var AddressUtil
     */
    protected $addressUtil;

    /**
     * The number of addresses to return.
     *
     * @var int
     */
    protected $amount = 1;

    public function __construct(
        Seed $seed,
        SecurityLevel $security,
        AddressUtil $addressUtil,
        int $startIndex = 0,
        bool $addChecksum = false,
        int $amount = 1
    ) {
        $this->seed = $seed;
        $this->security = $security;
        $this->startIndex = $startIndex;
        $this->addressUtil = $addressUtil;
        $this->addChecksum = $addChecksum;
        $this->amount = $amount;
    }

    public function execute(array $options = [])
    {
        $result = new Result($this);
        $index = $this->startIndex; // don't change the state

        for ($i = 0; $i < $this->amount; ++$i) {
            $trace = new Trace(AddressUtil::class);
            $trace->start();
            $address = $this->addressUtil->generateAddress(
                $this->seed,
                $index,
                $this->security,
                $this->addChecksum
            );
            $result->addAddress($address, $index);
            $result->addChildTrace($trace->stop());
            ++$index;
        }

        return $result->finish();
    }
}
