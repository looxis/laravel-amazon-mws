<?php

namespace Looxis\LaravelAmazonMWS\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Looxis\LaravelAmazonMWS\MWSClient;
use Looxis\LaravelAmazonMWS\MWSOrders;
use Orchestra\Testbench\TestCase;

class MWSOrdersTest extends TestCase
{
    /** @test */
    public function it_gets_an_amazon_order()
    {
        $content = file_get_contents('mocks/fetchOrder.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsOrders = new MWSOrders($mwsClient);
        $response = $mwsOrders->get('1234');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
