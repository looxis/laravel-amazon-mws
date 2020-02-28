# Laravel Amazon MWS

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/looxis/laravel-amazon-mws.svg?style=flat-square)](https://packagist.org/packages/looxis/laravel-amazon-mws)
![Travis (.org)](https://img.shields.io/travis/looxis/laravel-amazon-mws)
[![StyleCI](https://styleci.io/repos/242777921/shield?branch=master)](https://styleci.io/repos/242777921)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/looxis/laravel-amazon-mws/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/looxis/laravel-amazon-mws/?branch=master)

Simple Amazon Marketplace Web Service API Package for Laravel

This package is under development. Currently we have only implemented the endpoits we use.
Feel free to add the endpoints you need ([contribute](#contributing)).
A List of all available endpoints you can see under the endpoint [road map](#road-map)

## Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Marketplace](#marketplace)
	- [Get Order](#get-order)
- [Road Map](#road-map)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security](#security)
- [License](#license)

[Official Amazon MWS Documentation](https://docs.developer.amazonservices.com/en_US/dev_guide/index.html)

## Installation

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

This is the content of the published config file:

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

<a name="marketplace"></a>
### Marketplace
If you need to dynamically change the marketplaces you want to use, just set them in your code via the MWS Facade:

```php
AmazonMWS::setMarketplaces('FR'); 

AmazonMWS::setMarketplaces('DE', 'FR');  //to append multiple marketplaces to your request query strings.
```

<a name="get-order"></a>
### Get Order

```php
$orderResponse = AmazonMWS::orders()->get("1234-1234-1234"); //get amazon order by id

$orderResponse = AmazonMWS::orders()->get("1234-1234-1234", "123-123-123"); //get multiple orders
```
<a name="road-map"></a>
## Endpoint Road map

Laravel Amazon MWS is still under development. We have only added the endpoits we currently are using ourself. We decided to ship it in this early stage so you can help to add some endpoits or use  the already existing.

Endpoint List:

- [x] Orders ([MWS Documentation Overview](https://docs.developer.amazonservices.com/en_US/orders-2013-09-01/Orders_Overview.html))
    - [x] ListOrders
    - [ ] ListOrdersByNextToken
    - [x] GetOrder
    - [ ] ListOrderItems
    - [ ] ListOrderItemsByNextToken
    - [ ] GetServiceStatus
    - [ ] Orders Datatypes

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