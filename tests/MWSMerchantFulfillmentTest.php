<?php

namespace Looxis\LaravelAmazonMWS\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Looxis\LaravelAmazonMWS\MWSClient;
use Looxis\LaravelAmazonMWS\MWSMerchantFulfillment;
use Orchestra\Testbench\TestCase;

class MWSMerchantFulfillmentTest extends TestCase
{
    /** @test */
    public function it_gets_eligible_shipping_services()
    {
        $content = file_get_contents('mocks/fetchEligibleShippingServices.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsMerchantFulfillment = new MWSMerchantFulfillment($mwsClient);
        $params = [
            'ShipmentRequestDetails' => [
                'AmazonOrderId' => 'XXX-XXXXXXX-XXXXXXX',
                'ItemList' => [
                    'Item' => [
                        1 => [
                            'OrderItemId' => 'XXXXXXXXXXXXXX',
                            'Quantity' => 1,
                        ],
                    ],
                ],
                'ShipFromAddress' => [
                    'Name' => 'FOO GmbH',
                    'AddressLine1' => 'Bar Str.11',
                    'City' => 'Minden',
                    'StateOrProvinceCode' => 'NRW',
                    'PostalCode' => '32423',
                    'CountryCode' => 'DE',
                    'Email' => 'foo@example.com',
                    'Phone' => '123456789',
                ],
                'PackageDimensions' => [
                    'Length' => 30,
                    'Width' => 22,
                    'Height' => 14,
                    'Unit' => 'centimeters',
                ],
                'Weight' => [
                    'Value' => 700,
                    'Unit' => 'grams',
                ],
                'ShippingServiceOptions' => [
                    'DeliveryExperience' => 'DeliveryConfirmationWithoutSignature',
                    'CarrierWillPickUp' => 'true',
                    'LabelFormat' => 'ShippingServiceDefault',
                ],
            ],
        ]; //example data
        $response = $mwsMerchantFulfillment->getEligibleShippingServices($params);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
    }

    /** @test */
    public function it_can_create_shipment()
    {
        $content = file_get_contents('mocks/createShipment.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsMerchantFulfillment = new MWSMerchantFulfillment($mwsClient);
        $data = [
            'ShippingServiceId' => 'FEDEX_PTP_PRIORITY_OVERNIGHT',
            'ShipmentRequestDetails' => [
                'AmazonOrderId' => 'XXX-XXXXXXX-XXXXXXX',
                'ItemList' => [
                    'Item' => [
                        1 => [
                            'OrderItemId' => 'XXXXXXXXXXXXXX',
                            'Quantity' => 1,
                        ],
                    ],
                ],
                'ShipFromAddress' => [
                    'Name' => 'FOO GmbH',
                    'AddressLine1' => 'Bar Str.11',
                    'City' => 'Minden',
                    'StateOrProvinceCode' => 'NRW',
                    'PostalCode' => '32423',
                    'CountryCode' => 'DE',
                    'Email' => 'foo@example.com',
                    'Phone' => '123456789',
                ],
                'PackageDimensions' => [
                    'Length' => 30,
                    'Width' => 22,
                    'Height' => 14,
                    'Unit' => 'centimeters',
                ],
                'Weight' => [
                    'Value' => 700,
                    'Unit' => 'grams',
                ],
                'ShippingServiceOptions' => [
                    'DeliveryExperience' => 'DeliveryConfirmationWithoutSignature',
                    'CarrierWillPickUp' => 'true',
                    'LabelFormat' => 'ShippingServiceDefault',
                ],
            ],
        ]; //example data
        $response = $mwsMerchantFulfillment->createShipment($data);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('Shipment', $response['data']);
    }

    /** @test */
    public function it_gets_shipment()
    {
        $content = file_get_contents('mocks/getShipment.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsMerchantFulfillment = new MWSMerchantFulfillment($mwsClient);
        //example data
        $response = $mwsMerchantFulfillment->getShipment('6f77095e-9f75-47eb-aaab-a42d5428fa1a');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('Shipment', $response['data']);
    }

    /** @test */
    public function it_cancels_shipment()
    {
        $content = file_get_contents('mocks/cancelShipment.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsMerchantFulfillment = new MWSMerchantFulfillment($mwsClient);
        //example data
        $response = $mwsMerchantFulfillment->cancelShipment('6f77095e-9f75-47eb-aaab-a42d5428fa1a');
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('Shipment', $response['data']);
    }

    /** @test */
    public function it_gets_additional_seller_inputs()
    {
        $content = file_get_contents('mocks/getAdditionalSellerInputs.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsMerchantFulfillment = new MWSMerchantFulfillment($mwsClient);
        $data = [
            'OrderId' => 'XXX-XXXXXXX-XXXXXXX',
            'ShippingServiceId' => 'FEDEX_PTP_PRIORITY_OVERNIGHT',
            'ShipFromAddress' => [
                'Name' => 'FOO GmbH',
                'AddressLine1' => 'Bar Str.11',
                'City' => 'Minden',
                'StateOrProvinceCode' => 'NRW',
                'PostalCode' => '32423',
                'CountryCode' => 'DE',
                'Email' => 'foo@example.com',
                'Phone' => '123456789',
            ],
        ]; //example data
        $response = $mwsMerchantFulfillment->getAdditionalSellerInputs($data);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('ItemLevelFieldsList', $response['data']);
    }

    /** @test */
    public function it_gets_service_status()
    {
        $content = file_get_contents('mocks/getServiceStatus.xml', true);

        $mock = new MockHandler([
            new Response(200, [], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $mwsClient = new MWSClient($client);
        $mwsMerchantFulfillment = new MWSMerchantFulfillment($mwsClient);
        //example data
        $response = $mwsMerchantFulfillment->getServiceStatus();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('request_id', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('Status', $response['data']);
    }
}
