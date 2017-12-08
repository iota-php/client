<?php

declare(strict_types = 1);

namespace Techworker\IOTA\Test\RemoteApi;


use PHPUnit\Framework\TestCase;
use Techworker\IOTA\RemoteApi\Node;
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
    public function setUp():void
    {
        $this->httpClient = new FixtureHttpClient();
        $this->initValidRequest();
    }

    /**
     * Loads a fixture from a value and decodes it.
     *
     * @param string $fixtureFile
     * @return array
     */
    protected function loadFixture(string $fixtureFile):array
    {
        $fixture = trim(file_get_contents($fixtureFile));
        return [
            'raw' => $fixture,
            'decoded' => json_decode($fixture, true)
        ];
    }

    protected function generateStaticTryte($length, int $evolution)
    {
        $tryte = '';
        // not secure, just for testing
        $values = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ9';
        $pos = 0;
        for($i = 0; $i < $length - 1; $i++) {
            if(!isset($values[$pos])) {
                $pos = 0;
            }
            $tryte .= $values[$pos];
            $pos++;
        }

        $tryte .= $values[$evolution];

        return $tryte;
    }

    abstract protected function initValidRequest();
    abstract public function testRequestSerialization();
    abstract public function testResponse();
    abstract public function provideResponseMissing();

    /**
     * @dataProvider provideResponseMissing
     * @expectedException \RuntimeException
     * @param string $fixture
     * @param string $key
     */
    public function testResponseMissingKey(string $fixture, string $key)
    {
        $fixture = $this->loadFixture($fixture);
        $keys = explode('.', $key);
        array_unshift($keys, 'decoded');
        $unsetKey = array_pop($keys);
        $data = &$fixture;
        foreach($keys as $subKey) {
            $data = &$data[$subKey];
        }
        unset($data[$unsetKey]);

        $this->httpClient->setResponseFromFixture(200, $fixture['decoded']);
        $this->httpClient->commandRequest($this->request, new Node());
    }
}