<?php

namespace Looxis\LaravelAmazonMWS;

use GuzzleHttp\Client;

class MWSService
{

    public function __construct(MWSClient $mwsClient = null)
    {
        $this->mwsClient = $mwsClient ?: new MWSClient();
    }

    public function orders()
    {
        return new MWSOrders($this->mwsClient);
    }

    public function setMarketPlaces($countries)
    {
        $this->mwsClient->setMarketPlaces($countries);
    }

}