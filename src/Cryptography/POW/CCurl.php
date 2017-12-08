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

namespace Techworker\IOTA\Cryptography\POW;

use Techworker\IOTA\Type\Transaction;

/**
 * Class CCurl
 *
 * POW implementation using a call to the ccurl lib.
 */
class CCurl implements PowInterface
{
    /**
     * The path to the ccurl executables.
     *
     * @var string
     */
    protected $pathToCcurl;

    /**
     * C constructor.
     *
     * @param string $pathToCcurl
     */
    public function __construct(string $pathToCcurl)
    {
        $this->pathToCcurl = $pathToCcurl;
    }

    /**
     * Runs the c implementation.
     *
     * @param int         $minWeightMagnitude
     * @param Transaction $transaction
     *
     * @return string
     */
    public function execute(int $minWeightMagnitude, Transaction $transaction): string
    {
        $command = '%s/ccurl-cli %d %s 2>&1';
        $command = sprintf(
            $command,
            rtrim($this->pathToCcurl, '/'),
            $minWeightMagnitude,
            (string) $transaction
        );

        return exec($command);
    }
}
