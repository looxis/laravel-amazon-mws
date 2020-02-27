<?php

namespace Looxis\LaravelAmazonMWS\Tests;

use Looxis\LaravelAmazonMWS\MWSOrders;
use Looxis\LaravelAmazonMWS\MWSService;
use Orchestra\Testbench\TestCase;

class MWSServiceTest extends TestCase
{
    /** @test */
    public function get_orders_service()
    {
        $mws = new MWSService;
        $this->assertInstanceOf(MWSOrders::class, $mws->orders());
    }
}
