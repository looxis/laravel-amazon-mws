# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.1] - 2020-11-17
### Added
- Add Merchant Fulfillment Service Class
- Add Get Eligible Shipping Services Method
- Add Get Shipment Method
- Add Create Shipment Method
- Add Cancel Shipment Method
- Add Get Additional Seller Inputs Method
- Add Get Service Status Method (Merchant Fulfillment)
- Add readme notice for Amazon Seller Rest Api
## [0.2.0] - 2020-10-09
### Updated
- Add Laravel 8 Support

## [0.1.12] - 2020-10-09
### Added
- Add getFeedSubmissionResult() method on the MWSFeeds Class and parse to convenient response structure
- Add x-www-form-urlencoded header if action is GetFeedSubmissionResult in MWSClient

## [0.1.11] - 2020-10-06
### Fixed
- Take the default_market_place environment variable if given in MWSClient
- Set MWS_auth_token to null instead of empty string if not given

## [0.1.10] - 2020-08-03
### Added
- Add MWS Auth Token to MWSClient requests and amazon-mws config file

## [0.1.8] - 2020-04-14
### Updated
- Wrap Single List Order Results in an array
- Set data null if nothing gets returned

## [0.1.7] - 2020-04-11
### Updated
- Fix Set Marketplaces in MWS Service

## [0.1.6] - 2020-04-11
### Updated
- Fix Set Marketplaces

## [0.1.5] - 2020-04-10
### Added
- Add List Orders Endpoint

## [0.1.4] - 2020-03-06
### Updated
- Re Release

## [0.1.3] - 2020-03-06
### Updated
- Wrap Single Order Items in data for List Order Items

## [0.1.2] - 2020-03-06
### Updated
- Fix bug with amazon order id in get Items
- Edit Readme with correct Travis Shields Image

## [0.1.1] - 2020-03-06
### Added
- Add List Order Items functionality for Orders endpoint

## [0.1.0] - 2020-03-05
### Added
- Add Scrutinizer
- Advanced Readme
- Add guzzle client timeout
- parsed and casted order responses
- Add SubmitFeed functionality

## [0.0.1] - 2020-02-27
### Added
- Initial Release
- Orders Get Order Action
