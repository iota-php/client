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
 * Class Milestone.
 *
 * Contains information about a milestone.
 */
class Milestone extends Tip implements SerializeInterface
{
    /**
     * The current index of the milestone.
     *
     * @var int
     */
    protected $index;

    /**
     * Milestone constructor.
     *
     * @param string $milestone
     * @param int    $index
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $milestone, int $index)
    {
        if (81 !== \strlen($milestone)) {
            throw new \InvalidArgumentException(sprintf(
                'A milestone must be 81 chars long: %s',
                $milestone
            ));
        }

        parent::__construct($milestone);
        $this->index = $index;
    }

    /**
     * Gets the index of the milestone.
     *
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * Gets the array version of the instance.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'index' => $this->index,
        ]);
    }
}
