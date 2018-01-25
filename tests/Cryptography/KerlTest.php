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

namespace IOTA\Tests\Cryptography;

use PHPUnit\Framework\TestCase;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Tests\Container;
use IOTA\Type\Trytes;
use IOTA\Util\TritsUtil;
use IOTA\Util\TrytesUtil;

/**
 * @coversNothing
 */
class KerlTest extends TestCase
{
    public function dataGenerateTrytesAndHashes()
    {
        $data = [];
        $file = __DIR__.'/../../vendor/iotaledger/kerl/test_vectors/generateTrytesAndHashes';
        $handle = fopen($file, 'r');
        $f = true;
        while (false !== ($line = fgetcsv($handle, 10000, ','))) {
            if ($f) {
                $f = false;

                continue;
            }
            $data[] = $line;
        }
        //return $data;
        return array_splice($data, 0, 1000);
    }

    /**
     * @dataProvider dataGenerateTrytesAndHashes
     *
     * @param mixed $trytes
     * @param mixed $kerlHash
     */
    public function testGenerateTrytesAndHashes($trytes, $kerlHash)
    {
        $container = new Container();
        /** @var KerlFactory $kerlFactory */
        $kerlFactory = $container->get(KerlFactory::class);
        $kerl = $kerlFactory->factory();
        $trits = TrytesUtil::toTrits(new Trytes($trytes));
        $kerl->absorb($trits, 0);
        $hashTrits = [];
        $kerl->squeeze($hashTrits, 0, 243);
        $tryte = TritsUtil::toTrytes($hashTrits);

        static::assertEquals($kerlHash, (string) $tryte);
    }

    public function dataGenerateTrytesAndMultiSqueeze()
    {
        $data = [];
        $file = __DIR__.'/../../vendor/iotaledger/kerl/test_vectors/generateTrytesAndMultiSqueeze';
        $handle = fopen($file, 'r');
        $f = true;
        while (false !== ($line = fgetcsv($handle, 10000, ','))) {
            if ($f) {
                $f = false;

                continue;
            }
            $data[] = $line;
        }
        //return $data;
        return array_splice($data, 0, 1000);
    }

    /**
     * @dataProvider dataGenerateTrytesAndMultiSqueeze
     *
     * @param mixed $trytes
     * @param mixed $kerlHash
     */
    public function testGenerateTrytesAndMultiSqueeze($trytes, $kerlHash)
    {
        $container = new Container();
        /** @var KerlFactory $kerlFactory */
        $kerlFactory = $container->get(KerlFactory::class);
        $kerl = $kerlFactory->factory();
        $trits = TrytesUtil::toTrits(new Trytes($trytes));
        $kerl->absorb($trits, 0);
        $hashTrits = [];
        $kerl->squeeze($hashTrits, 0, 243);
        $tryte = TritsUtil::toTrytes($hashTrits);

        static::assertEquals($kerlHash, (string) $tryte);
    }

    public function dataGenerateMultiTrytesAndHashes()
    {
        $data = [];
        $file = __DIR__.'/../../vendor/iotaledger/kerl/test_vectors/generateMultiTrytesAndHash';
        $handle = fopen($file, 'r');
        $f = true;
        while (false !== ($line = fgetcsv($handle, 10000, ','))) {
            if ($f) {
                $f = false;

                continue;
            }
            $data[] = $line;
        }
        //return $data;
        return array_splice($data, 0, 1000);
    }

    /**
     * @dataProvider dataGenerateMultiTrytesAndHashes
     *
     * @param mixed $trytes
     * @param mixed $kerlHash
     */
    public function testGenerateMultiTrytesAndHashes($trytes, $kerlHash)
    {
        $container = new Container();
        /** @var KerlFactory $kerlFactory */
        $kerlFactory = $container->get(KerlFactory::class);
        $kerl = $kerlFactory->factory();

        $trits = TrytesUtil::toTrits(new Trytes($trytes));
        $kerl->absorb($trits, 0);
        $hashTrits = [];
        $kerl->squeeze($hashTrits, 0, 243);
        $tryte = TritsUtil::toTrytes($hashTrits);

        static::assertEquals($kerlHash, (string) $tryte);
    }
}
