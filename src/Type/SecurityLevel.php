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

namespace Techworker\IOTA\Type;

use Techworker\IOTA\SerializeInterface;

/**
 * Class SecurityLevel.
 *
 * Enumeration for the security level.
 */
class SecurityLevel implements SerializeInterface
{
    /**
     * 81-trits (low).
     *
     * @var int
     */
    private const LEVEL_1 = 1;

    /**
     * 162-trits (medium).
     *
     * @var int
     */
    private const LEVEL_2 = 2;

    /**
     * 243-trits (high)n.
     *
     * @var int
     */
    private const LEVEL_3 = 3;

    /**
     * The current level of the instance.
     *
     * @var int
     */
    private $level;

    /**
     * SecurityLevel constructor.
     *
     * @param int $level
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $level)
    {
        if ($level < 1 || $level > 3) {
            throw new \InvalidArgumentException('Invalid security level.');
        }

        $this->level = $level;
    }

    /**
     * Gets the level value.
     *
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Creates as new instance with level 1.
     *
     * @return SecurityLevel
     */
    public static function LEVEL_1(): self
    {
        return new self(self::LEVEL_1);
    }

    /**
     * Creates as new instance with level 2.
     *
     * @return SecurityLevel
     */
    public static function LEVEL_2(): self
    {
        return new self(self::LEVEL_2);
    }

    /**
     * Creates as new instance with level 3.
     *
     * @return SecurityLevel
     */
    public static function LEVEL_3(): self
    {
        return new self(self::LEVEL_3);
    }

    /**
     * Tries to cast the given value to int and returns the appropriate level
     * instance.
     *
     * @param mixed $value
     *
     * @return null|SecurityLevel
     */
    public static function fromValue($value): ?self
    {
        switch ((int) $value) {
            case 1:
                return self::LEVEL_1();
            case 2:
                return self::LEVEL_2();
            case 3:
                return self::LEVEL_3();
        }

        // TODO: throw exception? this should not happen!
        return null;
    }

    public function serialize(): int
    {
        return $this->getLevel();
    }
}
