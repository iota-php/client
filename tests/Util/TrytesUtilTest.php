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
use IOTA\Type\Address;
use IOTA\Type\Trytes;
use IOTA\Util\TrytesUtil;
use IOTA\Util\TryteUtil;

/**
 * @coversNothing
 */
class TrytesUtilTest extends TestCase
{
    public function testNullHashTrytes()
    {
        $nullHashTrytes = TrytesUtil::nullHashTrytes();
        static::assertEquals(str_repeat('9', 243), (string) $nullHashTrytes);
        static::assertInstanceOf(Trytes::class, $nullHashTrytes);
    }

    public function testToTrits()
    {
        $trytes = new Trytes('ABCDEFGHIJKLMNOPQRSTUVWXYZ9');

        $trits = [];
        foreach ($trytes as $tryte) {
            $trits[] = TryteUtil::TRYTE_TO_TRITS_MAP[$tryte][0];
            $trits[] = TryteUtil::TRYTE_TO_TRITS_MAP[$tryte][1];
            $trits[] = TryteUtil::TRYTE_TO_TRITS_MAP[$tryte][2];
        }

        static::assertEquals(count($trits), count(TrytesUtil::toTrits($trytes)));
        static::assertEquals($trits, TrytesUtil::toTrits($trytes));
    }

    public function testAsciiTrytes()
    {
        $string = '';
        for ($c = 0; $c <= 255; ++$c) {
            $string .= \chr($c);
        }
        $expected = '99A9B9C9D9E9F9G9H9I9J9K9L9M9N9O9P9Q9R9S9T9U9V9W9X9Y9Z99AAABACADAEAFAGAHAIAJAKALAMANAOAPAQARASATAUAVAWAXAYAZA9BABBBCBDBEBFBGBHBIBJBKBLBMBNBOBPBQBRBSBTBUBVBWBXBYBZB9CACBCCCDCECFCGCHCICJCKCLCMCNCOCPCQCRCSCTCUCVCWCXCYCZC9DADBDCDDDEDFDGDHDIDJDKDLDMDNDODPDQDRDSDTDUDVDWDXDYDZD9EAEBECEDEEEFEGEHEIEJEKELEMENEOEPEQERESETEUEVEWEXEYEZE9FAFBFCFDFEFFFGFHFIFJFKFLFMFNFOFPFQFRFSFTFUFVFWFXFYFZF9GAGBGCGDGEGFGGGHGIGJGKGLGMGNGOGPGQGRGSGTGUGVGWGXGYGZG9HAHBHCHDHEHFHGHHHIHJHKHLHMHNHOHPHQHRHSHTHUHVHWHXHYHZH9IAIBICIDIEIFIGIHIIIJIKILI';
        $trytes = TrytesUtil::asciiToTrytes($string);
        static::assertEquals($expected, $trytes);
        static::assertEquals($string, TrytesUtil::asciiFromTrytes($trytes));

        $string .= \chr(256);
        // encode all others with "99"
        $trytes = TrytesUtil::asciiToTrytes($string);
        static::assertEquals($expected.'99', (string) $trytes);
        static::assertEquals($string, TrytesUtil::asciiFromTrytes($trytes));
    }

    public function testAsciiTrytesNotEven()
    {
        static::assertEquals('', TrytesUtil::asciiFromTrytes(new Trytes('9')));
    }

    public function testStringToTrytes()
    {
        $trytes = str_repeat('A', 81);
        $trytesInstance = new Trytes(str_repeat('A', 81));
        static::assertEquals($trytes, (string) TrytesUtil::stringToTrytes($trytes));
        static::assertInstanceOf(Trytes::class, TrytesUtil::stringToTrytes($trytes));
        static::assertEquals($trytesInstance, TrytesUtil::stringToTrytes($trytesInstance));
        static::assertInstanceOf(Address::class, TrytesUtil::stringToTrytes($trytes, Address::class));
    }

    public function testArrayStringToTrytes()
    {
        $trytesArray = [str_repeat('A', 81), str_repeat('A', 80).'B'];

        static::assertCount(2, TrytesUtil::arrayToTrytes($trytesArray));
        static::assertInstanceOf(Trytes::class, TrytesUtil::arrayToTrytes($trytesArray)[0]);
        static::assertInstanceOf(Trytes::class, TrytesUtil::arrayToTrytes($trytesArray)[1]);
        static::assertEquals($trytesArray[0], (string) TrytesUtil::arrayToTrytes($trytesArray)[0]);
        static::assertEquals($trytesArray[1], (string) TrytesUtil::arrayToTrytes($trytesArray)[1]);

        static::assertCount(2, TrytesUtil::arrayToTrytes($trytesArray, Address::class));
        static::assertInstanceOf(Address::class, TrytesUtil::arrayToTrytes($trytesArray, Address::class)[0]);
        static::assertInstanceOf(Address::class, TrytesUtil::arrayToTrytes($trytesArray, Address::class)[1]);
        static::assertEquals($trytesArray[0], (string) TrytesUtil::arrayToTrytes($trytesArray, Address::class)[0]);
        static::assertEquals($trytesArray[1], (string) TrytesUtil::arrayToTrytes($trytesArray, Address::class)[1]);
    }
}
