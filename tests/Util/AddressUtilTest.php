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

namespace IOTA\Tests\Util;

use PHPUnit\Framework\TestCase;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Tests\Container;
use IOTA\Type\Address;
use IOTA\Type\SecurityLevel;
use IOTA\Type\Seed;
use IOTA\Util\AddressUtil;
use IOTA\Util\CheckSumUtil;

/**
 * Class AddressUtilTest.
 *
 * @coversNothing
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
     * @dataProvider addressDataProvider
     *
     * @param mixed $seed
     * @param mixed $index
     * @param mixed $securityLevel
     * @param mixed $expectedAddress
     * @param mixed $checksum
     */
    public function testGenerateAddress($seed, $index, $securityLevel, $expectedAddress, $checksum)
    {
        $container = new Container();
        $util = new AddressUtil($container->get(KerlFactory::class), $container->get(CheckSumUtil::class));
        $address = $util->generateAddress($seed, $index, $securityLevel, false);
        $addressWithChecksum = $util->generateAddress($seed, $index, $securityLevel, true);
        static::assertEquals($expectedAddress, (string) $address);
        static::assertEquals($expectedAddress.$checksum, (string) $addressWithChecksum);
    }

    /**
     * @dataProvider addressDataProvider
     *
     * @param mixed $seed
     * @param mixed $index
     * @param mixed $securityLevel
     * @param mixed $address
     * @param mixed $checksum
     */
    public function testGenerateChecksum($seed, $index, $securityLevel, $address, $checksum)
    {
        $container = new Container();
        $util = new AddressUtil($container->get(KerlFactory::class), $container->get(CheckSumUtil::class));
        $address = new Address($address);
        $checksum = $util->getChecksum($address);
        static::assertEquals($checksum, (string) $checksum);
    }
}
