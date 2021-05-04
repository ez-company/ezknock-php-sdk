<?php

namespace EZKnock\Test;

use EZKnock\Buyers;

class BuyersTest extends TestCase {
    public function testUserCreate() {
        $this->client->method('post')->willReturn('foo');

        $users = new Buyers($this->client);
        $this->assertSame('foo', $users->coverage(''));
    }
}
