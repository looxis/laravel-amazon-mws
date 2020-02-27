<?php

namespace Looxis\LaravelAmazonMWS\Tests;

use DateTime;
use Looxis\LaravelAmazonMWS\MWSClient;
use Looxis\LaravelAmazonMWS\MWSOrders;
use Looxis\LaravelAmazonMWS\MWSService;
use Orchestra\Testbench\TestCase;

class MWSServiceTest extends TestCase
{
    /** @test */
    function get_orders_service()
    {
        $mws = new MWSService;
        $this->assertInstanceOf(MWSOrders::class, $mws->orders());
    }
   
}