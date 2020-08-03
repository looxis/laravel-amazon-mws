<?php

namespace Looxis\LaravelAmazonMWS;

use GuzzleHttp\Client;
use Looxis\LaravelAmazonMWS\Exceptions\CountryIsMissingException;
use Looxis\LaravelAmazonMWS\Exceptions\CountryNotAvailableException;

class MWSClient
{
    const SIGNATURE_METHOD = 'HmacSHA256';
    const SIGNATURE_VERSION = '2';
    const DATE_FORMAT = "Y-m-d\TH:i:s.\\0\\0\\0\\Z";
    const APPLICATION_NAME = 'Looxis/MwsClient';
    const APPLICATION_VERSION = '0.0.1';

    protected $accessKeyId;
    protected $secretKey;
    protected $sellerId;
    protected $client;
    protected $marketPlaces;
    protected $mwsAuthToken;

    protected $marketplaceIds = [
        'A2Q3Y263D00KWC' => 'mws.amazonservices.com',
        'A2EUQ1WTGCTBG2' => 'mws.amazonservices.ca',
        'A1AM78C64UM0Y8' => 'mws.amazonservices.com.mx',
        'ATVPDKIKX0DER' => 'mws.amazonservices.com',
        'A2VIGQ35RCS4UG' => 'mws.amazonservices.ae',
        'A1PA6795UKMFR9' => 'mws-eu.amazonservices.com',
        'ARBP9OOSHTCHU' => 'mws-eu.amazonservices.com',
        'A1RKKUPIHCS9HS' => 'mws-eu.amazonservices.com',
        'A13V1IB3VIYZZH' => 'mws-eu.amazonservices.com',
        'A1F83G8C2ARO7P' => 'mws-eu.amazonservices.com',
        'A21TJRUUN4KGV' => 'mws.amazonservices.in',
        'APJ6JRA9NG5V4' => 'mws-eu.amazonservices.com',
        'A17E79C6D8DWNP' => 'mws-eu.amazonservices.com',
        'A33AVAJ2PDY3EV' => 'mws-eu.amazonservices.com',
        'A19VAU5U5O7RUS' => 'mws-fe.amazonservices.com',
        'A39IBJ37TRP1C6' => 'mws.amazonservices.com.au',
        'A1VC38T7YXB528' => 'mws.amazonservices.jp',
        'A1805IZSGTT6HS' => 'mws-eu.amazonservices.com',
    ];

    protected $countries = [
        'BR' => 'A2Q3Y263D00KWC',
        'CA' => 'A2EUQ1WTGCTBG2',
        'MX' => 'A1AM78C64UM0Y8',
        'US' => 'ATVPDKIKX0DER',
        'AE' => 'A2VIGQ35RCS4UG',
        'DE' => 'A1PA6795UKMFR9',
        'EG' => 'ARBP9OOSHTCHU',
        'ES' => 'A1RKKUPIHCS9HS',
        'FR' => 'A13V1IB3VIYZZH',
        'GB' => 'A1F83G8C2ARO7P',
        'IN' => 'A21TJRUUN4KGV',
        'IT' => 'APJ6JRA9NG5V4',
        'SA' => 'A17E79C6D8DWNP',
        'TR' => 'A33AVAJ2PDY3EV',
        'SG' => 'A19VAU5U5O7RUS',
        'AU' => 'A39IBJ37TRP1C6',
        'JP' => 'A1VC38T7YXB528',
        'NL' => 'A1805IZSGTT6HS',
    ];

    public function __construct(Client $client = null)
    {
        $this->accessKeyId = config('amazon-mws.access_key_id');
        $this->secretKey = config('amazon-mws.secret_key');
        $this->sellerId = config('amazon-mws.seller_id');
        $this->mwsAuthToken = config('amazon-mws.mws_auth_token');
        $this->marketPlaces = ['DE'];
        $this->client = $client ?: new Client(['timeout'  => 60]);
    }

    public function setMarketPlaces($countryCodes)
    {
        $countryCodes = is_array($countryCodes) ? $countryCodes : func_get_args();
        $this->marketPlaces = $countryCodes;
    }

    public function getCurrentMarketPlaces()
    {
        return $this->marketPlaces;
    }

    public function getTimeStamp()
    {
        return gmdate(self::DATE_FORMAT, time());
    }

    public function getAccessKeyId()
    {
        return $this->accessKeyId;
    }

    public function setAccessKeyId($key)
    {
        $this->accessKeyId = $key;

        return $this;
    }

    public function getSellerId()
    {
        return $this->sellerId;
    }

    public function setSellerId($id)
    {
        $this->sellerId = $id;

        return $this;
    }

    public function getMWSAuthToken()
    {
        return $this->mwsAuthToken;
    }

    public function getSignatureMethod()
    {
        return self::SIGNATURE_METHOD;
    }

    public function getSignatureVersion()
    {
        return self::SIGNATURE_VERSION;
    }

    public function getDomain()
    {
        $mainMarketPlace = $this->marketPlaces[0];
        if ($mainMarketPlace) {
            $marketPlaceId = $this->countries[$mainMarketPlace];

            return $this->marketplaceIds[$marketPlaceId];
        }

        throw new CountryIsMissingException();
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        if (in_array($country, array_keys($this->countries))) {
            $this->country = $country;

            return $this;
        } else {
            throw new CountryNotAvailableException();
        }
    }

    public function getMarketPlaceIds()
    {
        return $this->marketplaceIds;
    }

    public function post($action, $path, $version, $params = [], $body = null)
    {
        $headers = [
            'Accept' => 'application/xml',
            'x-amazon-user-agent' => self::APPLICATION_NAME.'/'.self::APPLICATION_VERSION,
        ];

        if ($action === 'SubmitFeed') {
            $headers['Content-Type'] = 'text/xml; charset=iso-8859-1';
        }

        $requestOptions = [
            'headers' => $headers,
            'body' => $body,
            'query' => $this->getQuery($path, $action, $version, $params),
        ];

        $uri = 'https://'.$this->getDomain().$path;
        $response = $this->client->post($uri, $requestOptions);
        $xmlResponse = simplexml_load_string($response->getBody()->getContents());
        $json = json_encode($xmlResponse);
        return json_decode($json, true);
    }

    public function getDefaultQueryParams($action, $version, $params = [])
    {
        $queryParameters = [
            'Action' => $action,
            'Timestamp' => $this->getTimeStamp(),
            'AWSAccessKeyId' => $this->getAccessKeyId(),
            'SellerId' => $this->getSellerId(),
            'MWSAuthToken' => $this->getMWSAuthToken(),
            'SignatureMethod' => $this->getSignatureMethod(),
            'SignatureVersion' => $this->getSignatureVersion(),
            'Version' => $version,
        ];
        $queryParameters = array_merge($queryParameters, $this->getMarketPlaceParams());
        $queryParameters = array_merge($queryParameters, $params);
        ksort($queryParameters);

        return $queryParameters;
    }

    public function getMarketPlaceParams()
    {
        $params = [];
        foreach ($this->marketPlaces as $index => $marketPlace) {
            $keyName = 'MarketplaceId.Id.'.($index + 1);
            $params[$keyName] = $this->countries[$marketPlace];
        }

        return $params;
    }

    public function generateRequestUri($action, $version, $params = [])
    {
        return http_build_query($this->getDefaultQueryParams($action, $version, $params), null, '&', PHP_QUERY_RFC3986);
    }

    public function getQueryStringForSignature($path, $action, $version, $params = [])
    {
        return  'POST'
                    ."\n"
                    .$this->getDomain()
                    ."\n"
                    .$path
                    ."\n"
                    .$this->generateRequestUri($action, $version, $params);
    }

    public function generateSignature($path, $action, $version, $params = [])
    {
        $signature = base64_encode(
            hash_hmac(
                'sha256',
                $this->getQueryStringForSignature($path, $action, $version, $params),
                $this->secretKey,
                true
            )
        );

        return $signature;
    }

    public function getQuery($path, $action, $version, $params = [])
    {
        $queryParameters = $this->getDefaultQueryParams($action, $version, $params);
        $queryParameters['Signature'] = $this->generateSignature($path, $action, $version, $params);

        return $queryParameters;
    }
}
