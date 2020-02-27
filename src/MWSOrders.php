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

        return $this->parseResponse($response);
    }

    protected function parseResponse($response)
    {
        return $response;
    }
}
