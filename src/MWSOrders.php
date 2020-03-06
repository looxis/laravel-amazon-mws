<?php

namespace Looxis\LaravelAmazonMWS;

class MWSOrders
{
    const VERSION = '2013-09-01';

    protected $client;

    public function __construct(MWSClient $client)
    {
        $this->client = $client;
    }

    public function list($params = [])
    {
        //TODO
    }

    public function get($ids)
    {
        $ids = is_array($ids) ? $ids : func_get_args();
        $params = [];

        foreach ($ids as $key => $id) {
            $keyName = 'AmazonOrderId.Id.'.($key + 1);
            $params[$keyName] = $id;
        }

        $response = $this->client->post('GetOrder', '/Orders/'.self::VERSION, self::VERSION, $params);

        return $this->parseResponse($response, 'GetOrderResult', 'Orders.Order');
    }

    public function getItems($id)
    {
        $params = [
            'AmazonOrderId' => $id,
        ];
        $response = $this->client->post('ListOrderItems', '/Orders/'.self::VERSION, self::VERSION, $params);

        return $this->parseResponse($response, 'ListOrderItemsResult', 'OrderItems.OrderItem');
    }

    protected function parseResponse($response, $resultTypeName, $dataName)
    {
        $requestId = data_get($response, 'ResponseMetadata.RequestId');
        $orders = data_get($response, $resultTypeName.'.'.$dataName);
        $nextToken = data_get($response, $resultTypeName.'.NextToken');

        $data = [
            'request_id' => $requestId,
            'data' => $orders,
        ];

        if ($nextToken) {
            $data['next_token'] = $nextToken;
        }

        if ($resultTypeName == 'ListOrderItemsResult') {
            $data['order_id'] = data_get($response, $resultTypeName.'.AmazonOrderId');
        }

        return $data;
    }
}
