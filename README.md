# Laravel Amazon MWS

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/looxis/laravel-amazon-mws.svg?style=flat-square)](https://packagist.org/packages/looxis/laravel-amazon-mws)
![Travis (.org)](https://img.shields.io/travis/looxis/laravel-amazon-mws)
[![StyleCI](https://styleci.io/repos/242777921/shield?branch=master)](https://styleci.io/repos/242777921)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/looxis/laravel-amazon-mws/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/looxis/laravel-amazon-mws/?branch=master)

Simple Amazon Marketplace Web Service API Package for Laravel

This package is under development. Currently we have only implemented the endpoints we are using.
Feel free to add the endpoints you need ([contribute](#contributing)).
A List of all available endpoints you can see under the endpoint [road map](#road-map).

## Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Authentication](#authentication)
    - [Marketplaces](#marketplaces)
    - [Orders](#orders)
	    - [Get Order](#get-order)
    - [Feeds](#feeds)
	    - [Submit Feed](#submit-feed)
    - [Responses](#responses)
    - [Exceptions](#exceptions)
- [Road Map](#road-map)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security](#security)
- [License](#license)

Link to the [Official Amazon MWS Documentation](https://docs.developer.amazonservices.com/en_US/dev_guide/index.html)

## Installation
This package requires PHP 7.3 and Laravel 6.0 or higher.

Require the package using composer:

```bash
composer require looxis/laravel-amazon-mws
```

The package will automatically register itself.

Add your Environment Variables for MWS to your .env File. The variable names are listed in the amazon-mws.php [config file](#configuration).

<a name="configuration"></a>
## Configuration

To successfully authenticate with the Amazon Marketplace Web Service you need to add the Environment variables to your `.env` File. The variable names are listed in the amazon-mws.php [config file](#configuration).
Also you can set a default marketplace.

You can optionally publish the configuration with:

```bash
$ php artisan vendor:publish --provider="Looxis\LaravelAmazonMWS\AmazonMWSServiceProvider" --tag="config"
```

This will create an `amazon-mws.php` in your config directory.

Config file content with the env variables:

```php
<?php

return [
    'access_key_id' => env('MWS_ACCESS_KEY_ID'),
    'secret_key' => env('MWS_SECRET_KEY'),
    'seller_id' => env('MWS_SELLER_ID'),
    'default_market_place' => env('MWS_DEFAULT_MARKET_PLACE', 'DE'),
];
```

<a name="usage"></a>
## Usage

<a name="authentication"></a>
### Authentication
Amazon MWS authenticates you via the [Canonicalized Query String](https://docs.developer.amazonservices.com/en_US/dev_guide/DG_QueryString.html). The Laravel Amazon MWS Package handles this for you and adds the string for each request. You just have to add your seller specific credentials to your .env file ([configuration](#configuration)).

<a name="marketplaces"></a>
### Marketplaces
If you need to change the marketplaces just set the country/countries in your code via the MWS Facade.
For simplicity the package chooses the right endpoint and market place id via the given country.
You do not have to set them by yourself.
([Amazon MWS endpoints and Market Place IDS Overview](https://docs.developer.amazonservices.com/en_US/dev_guide/DG_Endpoints.html)) If something is missing do not hesitate to create an issue.

```php
AmazonMWS::setMarketplaces('FR'); 

AmazonMWS::setMarketplaces('DE', 'FR');  //to append multiple marketplaces to your request query strings.
```

<a name="orders"></a>
### Orders
Retrieve order information that you need.
[Amazon MWS Orders Documentation Overview](https://docs.developer.amazonservices.com/en_US/orders-2013-09-01/Orders_Overview.htm)

<a name="get-order"></a>
#### Get Order
Returns orders based on the AmazonOrderId values that you specify.

```php
$response = AmazonMWS::orders()->get("1234-1234-1234"); //get amazon order by id

$response = AmazonMWS::orders()->get("1234-1234-1234", "123-123-123"); //get multiple orders
```
##### Throttling
- maximum request quota of six and a restore rate of one request every minute.
[MWS Throttling Algorithm](https://docs.developer.amazonservices.com/en_US/dev_guide/DG_Throttling.html)
- Throws a ServerException with `Request is throttled`

<a name="feeds"></a>
### Feeds
The Feeds API lets you upload inventory and order data to Amazon
[Amazon MWS Orders Documentation Overview](https://docs.developer.amazonservices.com/en_US/feeds/Feeds_Overview.html)

<a name="submit-feed"></a>
#### Submit Feed
Uploads a feed for processing by Amazon MWS.

You must set the feed type and content to successfully submit the feed.

```php
$feedXmlContent = '<?xml version="1.0"?> ...'; // based on the [type](https://docs.developer.amazonservices.com/en_US/feeds/Feeds_FeedType.html)
$response = AmazonMWS::feeds()
                ->setType("_POST_ORDER_ACKNOWLEDGEMENT_DATA_")
                ->setContent($xml)
                ->submit();
```
##### Throttling
- maximum request quota of 15 and a restore rate of one request every two minutes.
- Hourly request quote: 30 
[MWS Throttling Algorithm](https://docs.developer.amazonservices.com/en_US/dev_guide/DG_Throttling.html)
- Throws a ServerException with `Request is throttled`

<a name="responses"></a>
### Responses
The Amazon MWS XML responses are parsed and will be casted into a convenient array structure.
SubmitFeedResponse Example:

```php
[
    "request_id" => "e86f7299-9712-43e3-b290-b659da85b527"
    "data" => [
        "FeedSubmissionId" => "2291326430"
        "FeedType" => "_POST_ORDER_ACKNOWLEDGEMENT_DATA_"
        "SubmittedDate" => "2020-03-04T14:54:14+00:00"
        "FeedProcessingStatus" => "_SUBMITTED_"
    ]
]
```

<a name="exceptions"></a>
### Exceptions
The Laravel Amazon MWS package does not catch the Exceptions returned by guzzle. 
For Example for throttling ServerExceptions or missing Parameter Client Exceptions.


<a name="road-map"></a>
## Endpoint Road map

Laravel Amazon MWS is still under development. We have only added the endpoits we currently are using ourselfs. We decided to ship it in this early stage so you can help to add some endpoits or use  the already existing.

Endpoint List:

- [x] Orders ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/orders-2013-09-01/Orders_Overview.html))
    - [ ] ListOrders
    - [ ] ListOrdersByNextToken
    - [x] GetOrder
    - [ ] ListOrderItems
    - [ ] ListOrderItemsByNextToken
    - [ ] GetServiceStatus
    - [ ] Orders Datatypes
- [X] Feeds ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/feeds/Feeds_Overview.html))
    - [X] SubmitFeed
    - [ ] GetFeedSubmissionList
    - [ ] GetFeedSubmissionListByNextToken
    - [ ] GetFeedSubmissionCount
    - [ ] CancelFeedSubmissions
    - [ ] GetFeedSubmissionResult
- [ ] Easy Ship ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/easy_ship/EasyShip_Overview.html))
- [ ] Finances ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/finances/Finances_Overview.html))
- [ ] FulFillment Inbound Shipment ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/fba_inbound/FBAInbound_Overview.html))
- [ ] FulFillment Inventory ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/fba_inventory/FBAInventory_Overview.html))
- [ ] FulFillment Outbound Shipment ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/fba_outbound/FBAOutbound_Overview.html))
- [ ] Merchant Fulfillment ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/merch_fulfill/MerchFulfill_Overview.html))
- [ ] Products ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/products/Products_Overview.html))
- [ ] Recommendations ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/recommendations/Recommendations_Overview.html))
- [ ] Reports ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/reports/Reports_Overview.html))
- [ ] Sellers ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/sellers/Sellers_Overview.html))
- [ ] Shipment Invoicing ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/shipment_invoicing/ShipmentInvoicing_Overview.html))
- [ ] Subscriptions ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/subscriptions/Subscriptions_Overview.html))


<a name="testing"></a>
## Testing

``` bash
composer test
```

<a name="changelog"></a>
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

<a name="contributing"></a>
## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

<a name="security"></a>
## Security

If you discover any security related issues, please email dev@looxis.com instead of using the issue tracker.

## Credits

- [Christian Stefener](https://github.com/ChrisSFR)
- [Jannik Malken](https://github.com/mannikj)
- [All Contributors](../../contributors)

## About us
LOOXIS GmbH based in Minden, Germany.

LOOXIS is a manufacturer of personalised gift articles, which are sold throughout Europe in (photo) specialist shops and via our online shop under [www.looxis.com](https://looxis.de).

<a name="license"></a>
## License
[MIT](./LICENSE.md)