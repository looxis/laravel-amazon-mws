<?php

namespace Looxis\LaravelAmazonMWS\Tests;

use DateTime;
use GuzzleHttp\Client;
use Looxis\LaravelAmazonMWS\MWSClient;
use Orchestra\Testbench\TestCase;

class MWSClientTest extends TestCase
{
    /** @test */
    function get_time_stamp()
    {
        $client = new MWSClient();
        $timestamp = $client->getTimeStamp();
        $this->assertTrue(DateTime::createFromFormat('Y-m-d\TH:i:s.\\0\\0\\0\\Z', $timestamp) !== false);
    }

    /** @test */
    function get_domain()
    {
        $client = new MWSClient();
        $this->assertEquals('mws-eu.amazonservices.com', $client->getDomain());
    }

    /** @test */
    function set_access_key()
    {
        $client = new MWSClient();
        $client->setAccessKeyId('1234');
        $this->assertEquals('1234', $client->getAccessKeyId());
    }

    /** @test */
    function set_seller_id()
    {
        $client = new MWSClient();
        $client->setSellerId('12345');
        $this->assertEquals('12345', $client->getSellerId());
    }

    /** @test */
    function get_signature_method()
    {
        $client = new MWSClient();
        $this->assertEquals('HmacSHA256', $client->getSignatureMethod());
    }

    /** @test */
    function get_signature_version()
    {
        $client = new MWSClient();
        $this->assertEquals('2', $client->getSignatureVersion());
    }

    /** @test */
    function get_default_query_params()
    {
        $mwsClient = new MWSClient();
        $params = $mwsClient->getDefaultQueryParams('GetOrder', '2013-09-01');
        $this->assertIsArray($params);
        $this->assertArrayHasKey("AWSAccessKeyId", $params);
        $this->assertArrayHasKey("Action", $params);
        $this->assertArrayHasKey("MarketplaceId.Id.1", $params);
        $this->assertArrayHasKey("SellerId", $params);
        $this->assertArrayHasKey("SignatureMethod", $params);
        $this->assertArrayHasKey("SignatureVersion", $params);
        $this->assertArrayHasKey("Timestamp", $params);
        $this->assertArrayHasKey("Version", $params);
    }


    /** @test */
    function post_request()
    {
        $this->markTestIncomplete("TODO");
    }
}