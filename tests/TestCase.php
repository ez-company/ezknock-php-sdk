<?php

namespace EZKnock\Test;

use EZKnock\Client;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    /**
     * @var Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $client;

    protected function setUp(): void {
        parent::setUp();

        $this->client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
    }
}
