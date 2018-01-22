<?php
declare(strict_types=1);

namespace Techworker\IOTA\ClientApi\Address;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\AddressUtil;

class GetNewAddressActionTest extends TestCase
{
    public function testCreationOfNewAddress()
    {
        $seed = new Seed('NOCPLHETMOBRESIC9XBNBOJPEEZZPXHBHQUYKVPSBTKCKETQDMDRFX9DZSEYZSWURXRPEHASLIPQMRDNB');

        $getNewAddressAction = new GetNewAddressAction(
            $seed,
            $this->prophesize(SecurityLevel::class)->reveal(),
            $this->prophesize(AddressUtil::class)
        );

        
    }
}
