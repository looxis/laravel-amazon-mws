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
        $nextToken = data_get($params, 'NextToken');
        $action = 'ListOrders';

        if ($nextToken) {
            $action = 'ListOrdersByNextToken';
        }

        $response = $this->client->post($action, '/Orders/' . self::VERSION, self::VERSION, $params);
        return $this->parseResponse($response, $action. 'Result', 'Orders.Order');
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
        $data = data_get($response, $resultTypeName . '.' . $dataName);
        $nextToken = data_get($response, $resultTypeName . '.NextToken');
        $createdBefore = data_get($response, $resultTypeName . '.CreatedBefore');

        //Check if single list item and wrap
        if ((!data_get($data, '0')) && $resultTypeName == 'ListOrderItemsResult') {
            $data = [$data];
        }

        $data = [
            'request_id' => $requestId,
            'data' => $data,
        ];

        if ($nextToken) {
            $data['next_token'] = $nextToken;
        }

        if ($createdBefore) {
            $data['created_before'] = $createdBefore;
        }

        if ($resultTypeName == 'ListOrderItemsResult') {
            $data['order_id'] = data_get($response, $resultTypeName . '.AmazonOrderId');
        }

        return $data;
    }
}
