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
        $response = $mwsOrders->get('902-3159896-1390916');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
    }

    /** @test */
    public function fetch_order_items_for_an_order()
    {
        $content = file_get_contents('mocks/fetchOrderItems.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsOrders = new MWSOrders($mwsClient);
        $response = $mwsOrders->getItems('058-1233752-8214740');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('next_token', $response);
        $this->assertArrayHasKey('order_id', $response);
        $this->assertArrayHasKey('data', $response);
    }

    /** @test */
    public function single_order_item_gets_wrapped()
    {
        $content = file_get_contents('mocks/fetchOrderItemsSingle.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsOrders = new MWSOrders($mwsClient);
        $response = $mwsOrders->getItems('058-1233752-8214740');
        $this->assertNotNull($response['data'][0]['OrderItemId']);
    }
}
