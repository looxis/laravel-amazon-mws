<?php

namespace Looxis\LaravelAmazonMWS\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Looxis\LaravelAmazonMWS\MWSClient;
use Looxis\LaravelAmazonMWS\MWSFeeds;
use Orchestra\Testbench\TestCase;

class MWSFeedsTest extends TestCase
{
    /** @test */
    public function can_submit_a_feed()
    {
        $response = file_get_contents('mocks/submitFeedResponse.xml', true);
        $content = file_get_contents('mocks/exampleFeedSubmit.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $response),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsFeeds = new MWSFeeds($mwsClient);
        $mwsFeeds->setType('_POST_PRODUCT_DATA_');
        $mwsFeeds->setContent($content);
        $response = $mwsFeeds->submit();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
