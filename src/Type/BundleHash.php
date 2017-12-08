<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\Type;

use Techworker\IOTA\Util\TritsUtil;
use Techworker\IOTA\Util\TrytesUtil;

/**
 * Class BundleHash.
 *
 * Represents the hash of a bundle.
 */
class BundleHash extends Trytes
{
    /**
     * Gets a normalized version of the current hash.
     *
     * @return int[]
     */
    public function normalized()
    {
        $normalizedBundle = [];

        for ($i = 0; $i < 3; ++$i) {
            $sum = 0;
            for ($j = 0; $j < 27; ++$j) {
                $normalizedBundle[$i * 27 + $j] = (int) TritsUtil::toInt(
                    TrytesUtil::toTrits(new Trytes($this->trytes[$i * 27 + $j]))
                );
                $sum += $normalizedBundle[$i * 27 + $j];
            }

            if ($sum >= 0) {
                while ($sum-- > 0) {
                    for ($j = 0; $j < 27; ++$j) {
                        if ($normalizedBundle[$i * 27 + $j] > -13) {
                            --$normalizedBundle[$i * 27 + $j];
                            break;
                        }
                    }
                }
            } else {
                while ($sum++ < 0) {
                    for ($j = 0; $j < 27; ++$j) {
                        if ($normalizedBundle[$i * 27 + $j] < 13) {
                            ++$normalizedBundle[$i * 27 + $j];
                            break;
                        }
                    }
                }
            }
        }

        return $normalizedBundle;
    }
}
