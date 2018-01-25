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

namespace IOTA\Cryptography\POW;

use IOTA\Exception;
use IOTA\Type\Transaction;

/**
 * Class CCurl.
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
     *
     * @throws \IOTA\Exception
     */
    public function __construct(string $pathToCcurl)
    {
        $this->pathToCcurl = $pathToCcurl;
        $this->checkPath();
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

    /**
     * check if ccurl-cli exists and if it is executable.
     *
     * @throws \IOTA\Exception
     */
    protected function checkPath()
    {
        $path = rtrim($this->pathToCcurl, '/').'/ccurl-cli';

        if (!file_exists($path)) {
            throw new Exception($path.' not exists');
        }
        if (!is_executable($path)) {
            throw new Exception($path.' is not executable');
        }
    }
}
