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
 * Class Transfer.
 *
 * Contains data for a transfer.
 */
class Transfer implements SerializeInterface
{
    /**
     * The message.
     *
     * @var Trytes
     */
    protected $message;

    /**
     * The tag.
     *
     * @var Tag
     */
    protected $obsoleteTag;

    /**
     * The value to transfer.
     *
     * @var Iota
     */
    protected $value;

    /**
     * The address to transfer.
     *
     * @var Address
     */
    protected $recipientAddress;

    /**
     * Transfer constructor.
     */
    public function __construct()
    {
        $this->message = new Trytes();
        $this->value = Iota::ZERO();
        $this->obsoleteTag = new Tag('999999999999999999999999999');
    }

    /**
     * Gets the message to transfer.
     *
     * @return Trytes
     */
    public function getMessage(): Trytes
    {
        return $this->message;
    }

    /**
     * Sets the message to transfer.
     *
     * @param Trytes $message
     *
     * @return Transfer
     */
    public function setMessage(Trytes $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the tag.
     *
     * @return Tag
     */
    public function getObsoleteTag(): Tag
    {
        return $this->obsoleteTag;
    }

    /**
     * Sets the tag.
     *
     * @param Tag $obsoleteTag
     *
     * @return Transfer
     */
    public function setObsoleteTag(Tag $obsoleteTag): self
    {
        $this->obsoleteTag = $obsoleteTag;

        return $this;
    }

    /**
     * Gets the transfer value.
     *
     * @return Iota
     */
    public function getValue(): Iota
    {
        return $this->value;
    }

    /**
     * Sets the transfer value.
     *
     * @param Iota $value
     *
     * @return Transfer
     */
    public function setValue(Iota $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the recipient address.
     *
     * @return Address
     */
    public function getRecipientAddress(): Address
    {
        return $this->recipientAddress;
    }

    /**
     * Sets the recipient address.
     *
     * @param Address $recipientAddress
     *
     * @return Transfer
     */
    public function setRecipientAddress(Address $recipientAddress): self
    {
        $this->recipientAddress = $recipientAddress;

        return $this;
    }

    public function serialize(): array
    {
        return [
            'message' => $this->message->serialize(),
            'obsoleteTag' => $this->obsoleteTag->serialize(),
            'value' => $this->value->serialize(),
            'recipientAddress' => $this->recipientAddress->serialize(),
        ];
    }
}
