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

/**
 * Class TransactionHash.
 *
 * This class represents the hash of a transaction.
 */
class TransactionHash extends Trytes
{
    /**
     * Creates a new TransactionHash instance.
     *
     * @param string|null $transaction
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $transaction = null)
    {
        if (null !== $transaction && 81 !== \strlen($transaction)) {
            throw new \InvalidArgumentException(sprintf(
                'A transaction must be 81 chars long: %s',
                $transaction
            ));
        }

        parent::__construct($transaction);
    }
}
