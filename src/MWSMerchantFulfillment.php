<?php

namespace Looxis\LaravelAmazonMWS;

class MWSMerchantFulfillment
{
    const VERSION = '2015-06-01';

    protected $client;

    public function __construct(MWSClient $client)
    {
        $this->client = $client;
    }

    public function getEligibleShippingServices($params = [])
    {
        $action = 'GetEligibleShippingServices';

        $response = $this->client->post($action, '/MerchantFulfillment/'.self::VERSION, self::VERSION, $params);

        return $this->parseResponse($response, $action.'Result');
    }

    public function parseResponse($response, $resultTypeName)
    {
        $requestId = data_get($response, 'ResponseMetadata.RequestId');
        $data = data_get($response, $resultTypeName);

        $data = [
            'request_id' => $requestId,
            'data' => $data,
        ];

        return $data;
    }

    public function createShipment($params = [])
    {
        $action = 'CreateShipment';

        $response = $this->client->post($action, '/MerchantFulfillment/'.self::VERSION, self::VERSION, $params);

        return $this->parseResponse($response, $action.'Result');
    }

    public function cancelShipment($shipmentId)
    {
        $action = 'CancelShipment';

        $response = $this->client->post($action, '/MerchantFulfillment/'.self::VERSION, self::VERSION, [
            'ShipmentId' => $shipmentId,
        ]);

        return $this->parseResponse($response, $action.'Result');
    }

    public function getShipment($shipmentId)
    {
        $action = 'GetShipment';

        $response = $this->client->post($action, '/MerchantFulfillment/'.self::VERSION, self::VERSION, [
            'ShipmentId' => $shipmentId,
        ]);

        return $this->parseResponse($response, $action.'Result');
    }

    public function getServiceStatus()
    {
        $action = 'GetServiceStatus';

        $response = $this->client->post($action, '/MerchantFulfillment/'.self::VERSION, self::VERSION);

        return $this->parseResponse($response, $action.'Result');
    }

    public function getAdditionalSellerInputs($params)
    {
        $action = 'GetAdditionalSellerInputs';

        $response = $this->client->post($action, '/MerchantFulfillment/'.self::VERSION, self::VERSION, $params);

        return $this->parseResponse($response, $action.'Result');
    }
}
