<?php

namespace EZKnock\Test;

use EZKnock\Buyers;

class BuyersTest extends TestCase {

    public function testCoverage() {
        $this->client->method('post')->willReturn('foo');

        $buyers = new Buyers($this->client);
        $this->assertSame('foo', $buyers->coverage(''));
    }

    public function testGetOrder() {
        $this->client->method('get')->willReturn('foo');

        $buyers = new Buyers($this->client);
        $this->assertSame('foo', $buyers->getOrder(''));
    }

    public function testCreateOrder() {
        $this->client->method('post')->willReturn('foo');

        $buyers = new Buyers($this->client);
        $this->assertSame('foo', $buyers->createOrder([]));
    }
}
