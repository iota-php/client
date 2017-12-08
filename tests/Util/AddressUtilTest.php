<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Test\Util;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Cryptography\Kerl;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\AddressUtil;

/**
 * Class AddressUtilTest
 *
 * @package Techworker\IOTA\Test\Util
 */
class AddressUtilTest extends TestCase
{
    /**
     * Gets a list of test data.
     *
     * @return array
     */
    public function addressDataProvider()
    {
        $seed = new Seed('ABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJABCDEFGHIJA');
        return [
            //[seed, index, security, address, checksum]
            [$seed, 0, SecurityLevel::LEVEL_1(), 'YWDQRAFPKLAUDNKRHGJBCYNXRYLCTYJJLKUOOQYHFBRFRVLEQEEMPSZJTGLAVNYEIRJMBWNEAHKKUVTLW', 'LECYTGCYB'],
            [$seed, 1, SecurityLevel::LEVEL_1(), 'ROKHXMDAL9BFCZF9LWVHCCGZHMRUXJKWWPHOAXBMTQFPWZZ9ZCNRFAHLVTWKIEITYFQAADFDBMMHMNDWY', 'BWLPRERFX'],
            [$seed, 0, SecurityLevel::LEVEL_2(), 'XDUMMVWKVKBHLHDIHXLLOOCTLRORTRFSVCARHBA9ABVUTJEKIAWLWNBPQZXRYWZLUCRZPQ9IJEAOBADHZ', 'PUQBXTGID'],
            [$seed, 1, SecurityLevel::LEVEL_2(), 'CPNBNYCPEKVJYIAVCFFCQDYYKYHZTHVP9FFEBONYYEGUI9SXNWPVQJVMG9PVWMKZIAWGCHVKCINGAUWSB', 'OAG9RLKJB'],
            [$seed, 0, SecurityLevel::LEVEL_3(), '99DKCMHMLBTAA9SXWLRSBUFIHOIOPNBGUJOCDLSICNM9XOSGWOHGDSRIOQWF99HOMCYZBAERGZXVOAXRY', 'ZDVRYCHWC'],
            [$seed, 1, SecurityLevel::LEVEL_3(), 'SXOQV99R9AEEHEZBRDAHRVA9FXGZDWISZTCWYEYIKGXKGFRFUKVXNUFLZHUVKOBNMYHLYKKXABRCLJAIZ', 'RD9VQBPGA'],
        ];
    }

    /**
     *
     * @dataProvider addressDataProvider
     */
    public function testGenerateAddress($seed, $index, $securityLevel, $expectedAddress, $checksum)
    {
        $util = new AddressUtil($this->conta);
        $address = $util->generateAddress($seed, $index, $securityLevel, false);
        static::assertEquals($expectedAddress, (string)$address);
    }

    /**
     * @dataProvider dataProviderTestGenerateAddress
     */
    public function testGenerateChecksum($seed, $index, $securityLevel, $address, $checksum)
    {
        $util = new AddressUtil(new Kerl());
        $address = new Address($address);
        $checksum = $util->getChecksum($address);
        static::assertEquals($checksum, (string)$checksum);
    }
}