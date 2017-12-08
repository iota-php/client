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

namespace Techworker\IOTA\RemoteApi\Commands\FindTransactions;

use Techworker\IOTA\RemoteApi\AbstractRequest;
use Techworker\IOTA\RemoteApi\AbstractResponse;
use Techworker\IOTA\RemoteApi\Exception;
use Techworker\IOTA\Type\Address;
use Techworker\IOTA\Type\Approvee;
use Techworker\IOTA\Type\BundleHash;
use Techworker\IOTA\Type\Tag;
use Techworker\IOTA\Util\SerializeUtil;

/**
 * Class Action.
 *
 * Searches for transaction hashes that match the specified bundle-hashes,
 * addresses, tags or approvees. Using multiple of these parameters returns
 * the intersection of the values.
 *
 * @link https://iota.readme.io/docs/findtransactions
 */
class Request extends AbstractRequest
{
    /**
     * List of bundle hashes.
     *
     * @var BundleHash[]
     */
    protected $bundleHashes;

    /**
     * List of addresses.
     *
     * @var Address[]
     */
    protected $addresses;

    /**
     * List of tags.
     *
     * @var Tag[]
     */
    protected $tags;

    /**
     * List of approvee transaction hashes.
     *
     * @var Approvee[]
     */
    protected $approvees;

    /**
     * Sets all bundles hashes.
     *
     * @param BundleHash[] $bundleHashes
     *
     * @return Request
     */
    public function setBundleHashes(array $bundleHashes): self
    {
        $this->bundleHashes = [];
        foreach ($bundleHashes as $bundle) {
            $this->addBundleHash($bundle);
        }

        return $this;
    }

    /**
     * Adds a bundle hash.
     *
     * @param BundleHash $bundle
     *
     * @return Request
     */
    public function addBundleHash(BundleHash $bundle): self
    {
        $this->bundleHashes[] = $bundle;

        return $this;
    }

    /**
     * Gets the list of bundle hashes.
     *
     * @return BundleHash[]
     */
    public function getBundleHashes(): array
    {
        return $this->bundleHashes;
    }

    /**
     * Sets all addresses.
     *
     * @param Address[] $addresses
     *
     * @return Request
     */
    public function setAddresses(array $addresses): self
    {
        $this->addresses = [];
        foreach ($addresses as $address) {
            $this->addAddress($address);
        }

        return $this;
    }

    /**
     * Adds a single address.
     *
     * @param Address $address
     *
     * @return Request
     */
    public function addAddress(Address $address): self
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Gets the list of addresses.
     *
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * Sets the tags.
     *
     * @param Tag[] $tags
     *
     * @return Request
     */
    public function setTags(array $tags): self
    {
        $this->tags = [];
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * Adds a single tag.
     *
     * @param Tag $tag
     *
     * @return Request
     */
    public function addTag(Tag $tag): self
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Gets the list of tags.
     *
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Sets the list of approvees.
     *
     * @param \string[] $approvees
     *
     * @return Request
     */
    public function setApprovees(array $approvees): self
    {
        $this->approvees = [];
        foreach ($approvees as $approvee) {
            $this->addApprovee($approvee);
        }

        return $this;
    }

    /**
     * Adds a single approvee.
     *
     * @param Approvee $approvee
     * @return Request
     */
    public function addApprovee(Approvee $approvee): self
    {
        $this->approvees[] = $approvee;

        return $this;
    }

    /**
     * Gets the list of approvees.
     *
     * @return Approvee[]
     */
    public function getApprovees(): array
    {
        return $this->approvees;
    }

    /**
     * Gets the data that should be sent to the nodes endpoint.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $params = [
            'command' => 'findTransactions',
        ];

        if (\count($this->bundleHashes) > 0) {
            $params['bundles'] = array_map('\strval', $this->bundleHashes);
        }

        if (\count($this->addresses) > 0) {
            $params['addresses'] = array_map(function (Address $address) {
                $address->removeChecksum();
                return (string)$address;
            }, $this->addresses);
        }

        if (\count($this->tags) > 0) {
            $params['tags'] = array_map('\strval', $this->tags);
        }

        if (\count($this->approvees) > 0) {
            $params['approvees'] = array_map('\strval', $this->approvees);
        }

        return $params;
    }

    /**
     * Executes the request.
     *
     * @return AbstractResponse|Response
     * @throws Exception
     */
    public function execute(): Response
    {
        $response = new Response($this);
        $srvResponse = $this->httpClient->commandRequest($this);
        $response->initialize($srvResponse['code'], $srvResponse['raw']);

        return $response->finish()->throwOnError();
    }

    public function serialize()
    {
        return array_merge(parent::serialize(), [
            'bundleHashes' => SerializeUtil::serializeArray($this->bundleHashes),
            'addresses' => SerializeUtil::serializeArray($this->addresses),
            'tags' => SerializeUtil::serializeArray($this->tags),
            'approvees' => SerializeUtil::serializeArray($this->approvees),
        ]);
    }
}
