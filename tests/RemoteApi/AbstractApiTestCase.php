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

namespace IOTA\Tests\RemoteApi;

use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Http\Mock\Client;
use IOTA\RemoteApi\ActionInterface;
use IOTA\RemoteApi\NodeApiClient;

abstract class AbstractApiTestCase extends TestCase
{
    /**
     * A valid and instantiated request object.
     *
     * @var ActionInterface
     */
    protected $action;

    /**
     * Initializes the http client.
     */
    public function setUp(): void
    {
        $this->initValidAction();
    }

    public function getNodeApiClient(Client $client)
    {
        return new NodeApiClient($client, $client, MessageFactoryDiscovery::find());
    }

    abstract public function testRequestSerialization();

    abstract public function testResponse();

    abstract public function provideResponseMissing();

    /**
     * Loads a fixture from a value and decodes it.
     *
     * @param string $fixtureFile
     *
     * @return array
     */
    protected function loadFixture(string $fixtureFile): array
    {
        $fixture = trim(file_get_contents($fixtureFile));

        return [
            'raw' => $fixture,
            'decoded' => json_decode($fixture, true),
        ];
    }

    protected function generateStaticTryte($length, int $evolution)
    {
        $tryte = '';
        // not secure, just for testing
        $values = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ9';
        $pos = 0;
        for ($i = 0; $i < $length - 1; ++$i) {
            if (!isset($values[$pos])) {
                $pos = 0;
            }
            $tryte .= $values[$pos];
            ++$pos;
        }

        $tryte .= $values[$evolution];

        return $tryte;
    }

    abstract protected function initValidAction();
}
