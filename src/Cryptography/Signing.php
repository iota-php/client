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

namespace Techworker\IOTA\Cryptography;

use Techworker\IOTA\Cryptography\Hashing\KerlFactory;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\SecurityLevel;
use Techworker\IOTA\Type\Seed;
use Techworker\IOTA\Util\TritsUtil;
use Techworker\IOTA\Util\TrytesUtil;

/**
 * Class Signing.
 */
class Signing
{
    /**
     * Gets the key for the given data.
     *
     * @param KerlFactory   $kerlFactory
     * @param Seed          $seed
     * @param int           $index
     * @param SecurityLevel $security
     *
     * @return array
     */
    public static function key(KerlFactory $kerlFactory, Seed $seed, int $index, SecurityLevel $security): array
    {
        $indexTrits = TritsUtil::fromInt((string) $index, 243);
        $subSeed = Adder::add(TrytesUtil::toTrits($seed), $indexTrits);

        $kerl = $kerlFactory->factory();
        $kerl->absorb($subSeed, 0, \count($subSeed));
        $kerl->squeeze($subSeed, 0, \count($subSeed));
        $kerl->reset();
        $kerl->absorb($subSeed, 0, \count($subSeed));

        $key = [];
        $offset = 0;
        $buffer = [];
        $securityLevel = $security->getLevel();
        while ($securityLevel-- > 0) {
            for ($i = 0; $i < 27; ++$i) {
                $kerl->squeeze($buffer, 0, \count($subSeed));
                for ($j = 0; $j < 243; ++$j) {
                    $key[$offset++] = $buffer[$j];
                }
            }
        }

        return $key;
    }

    /**
     * Gets the digests for the key.
     *
     * @param KerlFactory $kerlFactory
     * @param array       $key
     *
     * @return array
     */
    public static function digests(KerlFactory $kerlFactory, array $key): array
    {
        $digests = new \SplFixedArray(\count($key) / 27);
        $buffer = [];

        $length = floor(\count($key) / 6561);
        for ($i = 0; $i < $length; ++$i) {
            $keyFragment = \array_slice($key, $i * 6561, 6561);
            for ($j = 0; $j < 27; ++$j) {
                $buffer = \array_slice($keyFragment, $j * 243, 243);
                for ($k = 0; $k < 26; ++$k) {
                    $kerl = $kerlFactory->factory();
                    $kerl->initialize();
                    $kerl->reset();
                    $kerl->absorb($buffer, 0, \count($buffer));
                    $kerl->squeeze($buffer, 0, $kerl->hashLength());
                }
                for ($k = 0; $k < 243; ++$k) {
                    $keyFragment[$j * 243 + $k] = $buffer[$k];
                }
            }

            $kerl = $kerlFactory->factory();
            $kerl->initialize();
            $kerl->reset();
            $kerl->absorb($keyFragment, 0, \count($keyFragment));
            $kerl->squeeze($buffer, 0, $kerl->hashLength());

            for ($j = 0; $j < 243; ++$j) {
                $digests[$i * 243 + $j] = $buffer[$j];
            }
        }

        return $digests->toArray();
    }

    /**
     * Returns a new address based on the given digest.
     *
     * @param KerlFactory $kerlFactory
     * @param array       $digests
     * @param int         $index
     *
     * @return Address
     */
    public static function address(KerlFactory $kerlFactory, array $digests, int $index): Address
    {
        $address = [];
        $kerl = $kerlFactory->factory();
        $kerl->absorb($digests, 0, \count($digests));
        $kerl->squeeze($address, 0, $kerl->hashLength());

        return new Address((string) TritsUtil::toTrytes($address), $index);
    }

    /**
     * Signs a fragment.
     *
     * @param KerlFactory $kerlFactory
     * @param array       $normalizedBundleFragment
     * @param array       $keyFragment
     *
     * @return array
     */
    public static function signatureFragment(KerlFactory $kerlFactory, array $normalizedBundleFragment, array $keyFragment): array
    {
        $signatureFragment = $keyFragment;

        for ($i = 0; $i < 27; ++$i) {
            $hash = \array_slice($signatureFragment, $i * 243, 243);
            for ($j = 0; $j < 13 - $normalizedBundleFragment[$i]; ++$j) {
                $kerl = $kerlFactory->factory();
                $kerl->initialize();
                $kerl->reset();
                $kerl->absorb($hash, 0, \count($hash));
                $kerl->squeeze($hash, 0, $kerl->hashLength());
            }

            for ($j = 0; $j < 243; ++$j) {
                $signatureFragment[$i * 243 + $j] = $hash[$j];
            }
        }

        return $signatureFragment;
    }
}
