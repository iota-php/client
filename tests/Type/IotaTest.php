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

namespace Techworker\IOTA\Tests\Type;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Type\Iota;

/**
 * @coversNothing
 */
class IotaTest extends TestCase
{
    protected $testData = [
        Iota::UNIT_PETA => [
            ['1', '1000000000000000'],
            ['1.2', '1200000000000000'],
            ['1.23', '1230000000000000'],
            ['1.234', '1234000000000000'],
            ['1.2345', '1234500000000000'],
            ['1.23456', '1234560000000000'],
            ['1.234567', '1234567000000000'],
            ['1.2345678', '1234567800000000'],
            ['1.23456789', '1234567890000000'],
            ['1.234567891', '1234567891000000'],
            ['1.2345678912', '1234567891200000'],
            ['1.23456789123', '1234567891230000'],
            ['1.234567891234', '1234567891234000'],
            ['1.2345678912345', '1234567891234500'],
            ['1.23456789123456', '1234567891234560'],
            ['1.234567891234567', '1234567891234567'],
        ],
        Iota::UNIT_TERA => [
            ['1', '1000000000000'],
            ['1.2', '1200000000000'],
            ['1.23', '1230000000000'],
            ['1.234', '1234000000000'],
            ['1.2345', '1234500000000'],
            ['1.23456', '1234560000000'],
            ['1.234567', '1234567000000'],
            ['1.2345678', '1234567800000'],
            ['1.23456789', '1234567890000'],
            ['1.234567891', '1234567891000'],
            ['1.2345678912', '1234567891200'],
            ['1.23456789123', '1234567891230'],
            ['1.234567891234', '1234567891234'],
        ],
        Iota::UNIT_GIGA => [
            ['1', '1000000000'],
            ['1.2', '1200000000'],
            ['1.23', '1230000000'],
            ['1.234', '1234000000'],
            ['1.2345', '1234500000'],
            ['1.23456', '1234560000'],
            ['1.234567', '1234567000'],
            ['1.2345678', '1234567800'],
            ['1.23456789', '1234567890'],
            ['1.234567891', '1234567891'],
        ],
        Iota::UNIT_MEGA => [
            ['1', '1000000'],
            ['1.2', '1200000'],
            ['1.23', '1230000'],
            ['1.234', '1234000'],
            ['1.2345', '1234500'],
            ['1.23456', '1234560'],
            ['1.234567', '1234567'],
        ],
        Iota::UNIT_KILO => [
            ['1', '1000'],
            ['1.2', '1200'],
            ['1.23', '1230'],
            ['1.234', '1234'],
        ],
    ];

    public function testPlus()
    {
        $iota1 = new Iota('10');
        $iota2 = new Iota('50');
        static::assertEquals((new Iota('60'))->getAmount(), $iota1->plus($iota2)->getAmount());
    }

    public function testMinus()
    {
        $iota1 = new Iota('50');
        $iota2 = new Iota('10');
        static::assertEquals((new Iota('40'))->getAmount(), $iota1->minus($iota2)->getAmount());
    }

    public function testDivide()
    {
        $iota1 = new Iota('50');
        static::assertEquals((new Iota('5'))->getAmount(), $iota1->divideBy(10)->getAmount());
    }

    public function testMultiply()
    {
        $iota1 = new Iota('50');
        static::assertEquals((new Iota('500'))->getAmount(), $iota1->multiplyBy(10)->getAmount());
    }

    public function provideTestFromPetaIota()
    {
        return $this->testData[Iota::UNIT_PETA];
    }

    public function provideTestFromTeraIota()
    {
        return $this->testData[Iota::UNIT_TERA];
    }

    public function provideTestFromGigaIota()
    {
        return $this->testData[Iota::UNIT_GIGA];
    }

    public function provideTestFromMegaIota()
    {
        return $this->testData[Iota::UNIT_MEGA];
    }

    public function provideTestFromKiloIota()
    {
        return $this->testData[Iota::UNIT_KILO];
    }

    /**
     * @dataProvider provideTestFromPetaIota
     *
     * @param mixed $peta
     * @param mixed $iota
     */
    public function testFromToPetaIota($peta, $iota)
    {
        static::assertEquals(Iota::fromPetaIota($peta)->getAmount(), $iota);
        static::assertEquals((new Iota($iota))->getPetaIota(), $peta);
    }

    /**
     * @dataProvider provideTestFromTeraIota
     *
     * @param mixed $tera
     * @param mixed $iota
     */
    public function testFromToTeraIota($tera, $iota)
    {
        static::assertEquals(Iota::fromTeraIota($tera)->getAmount(), $iota);
        static::assertEquals((new Iota($iota))->getTeraIota(), $tera);
    }

    /**
     * @dataProvider provideTestFromGigaIota
     *
     * @param mixed $giga
     * @param mixed $iota
     */
    public function testFromToGigaIota($giga, $iota)
    {
        static::assertEquals(Iota::fromGigaIota($giga)->getAmount(), $iota);
        static::assertEquals((new Iota($iota))->getGigaIota(), $giga);
    }

    /**
     * @dataProvider provideTestFromMegaIota
     *
     * @param mixed $mega
     * @param mixed $iota
     */
    public function testFromToMegaIota($mega, $iota)
    {
        static::assertEquals(Iota::fromMegaIota($mega)->getAmount(), $iota);
        static::assertEquals((new Iota($iota))->getMegaIota(), $mega);
    }

    /**
     * @dataProvider provideTestFromKiloIota
     *
     * @param mixed $kilo
     * @param mixed $iota
     */
    public function testFromToKiloIota($kilo, $iota)
    {
        static::assertEquals(Iota::fromKiloIota($kilo)->getAmount(), $iota);
        static::assertEquals((new Iota($iota))->getKiloIota(), $kilo);
    }

    public function testInvalidAmount()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Iota('2779530283277762');
    }

    public function testCompare()
    {
        $iota = new Iota(1000);
        $lower = new Iota(1);
        $greater = new Iota(2000);

        static::assertTrue($iota->lt($greater));
        static::assertTrue($iota->gt($lower));
        static::assertTrue($iota->eq($iota));
        static::assertTrue($iota->gteq($iota));
        static::assertTrue($iota->gteq($lower));
        static::assertTrue($iota->lteq($iota));
        static::assertTrue($iota->lteq($greater));
        static::assertTrue($iota->neq($greater));
        static::assertTrue($iota->neq($lower));
        static::assertFalse($iota->neq($iota));

        static::assertFalse($iota->gt($greater));
        static::assertFalse($iota->lt($lower));
        static::assertFalse($iota->eq($lower));
        static::assertFalse($iota->gteq($greater));
        static::assertFalse($iota->lteq($lower));
    }

    public function testZero()
    {
        $z = Iota::ZERO();
        static::assertEquals('0', $z->getAmount());
    }

    public function testIsNeg()
    {
        $z = new Iota('-1');
        static::assertTrue($z->isNeg());
        $z = new Iota('1');
        static::assertFalse($z->isNeg());
    }

    public function testIsPos()
    {
        $z = new Iota('-1');
        static::assertFalse($z->isPos());
        $z = new Iota('1');
        static::assertTrue($z->isPos());
    }

    public function testIsZero()
    {
        $z = Iota::ZERO();
        static::assertTrue($z->isZero());
        $z = new Iota('1');
        static::assertFalse($z->isZero());
        $z = new Iota('-1');
        static::assertFalse($z->isZero());
    }

    public function testSerialize()
    {
        $z = Iota::ZERO();
        static::assertEquals('0', $z->serialize());
        $z = new Iota('1');
        static::assertEquals('1', $z->serialize());
        $z = new Iota('-1');
        static::assertEquals('-1', $z->serialize());
    }
}
