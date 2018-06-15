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

use IOTA\RemoteApi\Actions\GetBalances\Action;
use IOTA\RemoteApi\Actions\GetBalances\Result;
use IOTA\Type\Address;
use IOTA\Type\Milestone;

class GetBalancesTest extends AbstractApiTestCase
{
    public function testRequestSerialization()
    {
        $expected = [
            'command' => 'getBalances',
            'addresses' => [
                $this->generateStaticTryte(81, 0),
                $this->generateStaticTryte(81, 1),
            ],
            'threshold' => 99,
        ];
        static::assertEquals($expected, $this->action->jsonSerialize());
    }

    public function testRequestInvalidAddresses()
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new Action(['abc']);
    }

    public function testResponse()
    {
        $fixture = $this->loadFixture(__DIR__.'/fixtures/GetBalances.json');
        $this->httpClient->setResponseFromFixture(200, $fixture['raw']);

        /** @var Response $response */
        $response = $this->action->execute();

        static::assertEquals([114544444], $response->getBalances());
        static::assertInstanceOf(Milestone::class, $response->getMilestone());
        static::assertEquals('INRTUYSZCWBHGFGGXXPWRWBZACYAFGVRRP9VYEQJOHYD9URMELKWAFYFMNTSP9MCHLXRGAFMBOZPZ9999', (string) $response->getMilestone());
        static::assertEquals(128, $response->getMilestone()->getIndex());
    }

    public function provideResponseMissing()
    {
        return [
            [__DIR__.'/fixtures/GetBalances.json', 'balances'],
            [__DIR__.'/fixtures/GetBalances.json', 'milestone'],
            [__DIR__.'/fixtures/GetBalances.json', 'milestoneIndex'],
        ];
    }

    protected function initValidAction()
    {
        $this->markTestSkipped('TODO');
        $this->action = new Action([
            new Address($this->generateStaticTryte(81, 0)),
            new Address($this->generateStaticTryte(81, 1)),
        ], 99);
    }
}
