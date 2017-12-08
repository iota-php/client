<?php

declare(strict_types = 1);
namespace Techworker\IOTA\Base\Types\Test;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\Cryptography\Kerl;
use Techworker\IOTA\Type\Trits;
use Techworker\IOTA\Type\Trytes;

class KerlTest extends TestCase
{
    public function dataGenerateTrytesAndHashes()
    {
        $data = [];
        $file = __DIR__ . '/../../../vendor/iotaledger/kerl/test_vectors/generateTrytesAndHashes';
        $handle = fopen($file, "r");
        $f = true;
        while (($line = fgetcsv($handle, 10000, ',')) !== false) {
            if ($f) {
                $f = false;
                continue;
            }
            $data[] = $line;
        }
        return array_splice($data, 0, 1000);
    }

    /**
     * @dataProvider dataGenerateTrytesAndHashes
     */
    public function testGenerateTrytesAndHashes($trytes, $kerlHash)
    {
        $kerl = new Kerl();
        $trits = (new Trytes($trytes))->toTrits();
        $kerl->absorb($trits, 0);
        $hashTrits = new Trits();
        $kerl->squeeze($hashTrits, 0, 243);
        $tryte = Trytes::createFromTrits($hashTrits);

        static::assertEquals($kerlHash, (string)$tryte);
    }

    public function dataGenerateTrytesAndMultiSqueeze()
    {
        $data = [];
        $file = __DIR__ . '/../../../vendor/iotaledger/kerl/test_vectors/generateTrytesAndMultiSqueeze';
        $handle = fopen($file, "r");
        $f = true;
        while (($line = fgetcsv($handle, 10000, ',')) !== false) {
            if ($f) {
                $f = false;
                continue;
            }
            $data[] = $line;
        }
        return array_splice($data, 0, 1000);
    }

    /**
     * @dataProvider dataGenerateTrytesAndMultiSqueeze
     */
    public function testGenerateTrytesAndMultiSqueeze($trytes, $kerlHash)
    {
        $kerl = new Kerl();
        $trits = (new Trytes($trytes))->toTrits();
        $kerl->absorb($trits, 0);
        $hashTrits = new Trits();
        $kerl->squeeze($hashTrits, 0, 243);
        $tryte = Trytes::createFromTrits($hashTrits);

        static::assertEquals($kerlHash, (string)$tryte);
    }

    public function dataGenerateMultiTrytesAndHashes()
    {
        $data = [];
        $file = __DIR__ . '/../../../vendor/iotaledger/kerl/test_vectors/generateMultiTrytesAndHash';
        $handle = fopen($file, "r");
        $f = true;
        while (($line = fgetcsv($handle, 10000, ',')) !== false) {
            if ($f) {
                $f = false;
                continue;
            }
            $data[] = $line;
        }
        return array_splice($data, 0, 1000);
    }

    /**
     * @dataProvider dataGenerateMultiTrytesAndHashes
     */
    public function testGenerateMultiTrytesAndHashes($trytes, $kerlHash)
    {
        $kerl = new Kerl();
        $trits = TrytesUtil::toTrits(new Trytes($trytes));
        $kerl->absorb($trits, 0);
        $hashTrits = new Trits();
        $kerl->squeeze($hashTrits, 0, 243);
        $tryte = Trytes::createFromTrits($hashTrits);

        static::assertEquals($kerlHash, (string)$tryte);
    }

}