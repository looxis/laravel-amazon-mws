<?php

return [
    'access_key_id' => env('MWS_ACCESS_KEY_ID'),
    'secret_key' => env('MWS_SECRET_KEY'),
    'seller_id' => env('MWS_SELLER_ID'),
    'default_market_place' => env('MWS_DEFAULT_MARKET_PLACE', 'DE'),
];
