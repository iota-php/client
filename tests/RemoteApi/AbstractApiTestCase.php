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

namespace Techworker\IOTA\Tests\RemoteApi;

use PHPUnit\Framework\TestCase;
use Techworker\IOTA\RemoteApi\RequestInterface;

abstract class AbstractApiTestCase extends TestCase
{
    /**
     * The http client that works with fixtures instead of doing real requests.
     *
     * @var FixtureHttpClient
     */
    protected $httpClient;

    /**
     * A valid and instantiated request object.
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Initializes the http client.
     */
    public function setUp(): void
    {
        $this->httpClient = new FixtureHttpClient();
        $this->initValidRequest();
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

    abstract protected function initValidRequest();
}
