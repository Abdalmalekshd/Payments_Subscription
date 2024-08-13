<?php

return [
    'api_key' => env('SHOPIFY_API_KEY', env('SHOPIFY_API_KEY')),
    'api_secret' => env('SHOPIFY_API_SECRET', env('SHOPIFY_API_SECRET')),
    'api_version' => env('SHOPIFY_API_VERSION', '2021-01'),
    'redirect_uri' => env('SHOPIFY_REDIRECT_URI', env('SHOPIFY_REDIRECT_URI')),
    'scopes' => env('SHOPIFY_APP_SCOPE', 'read_products,write_products'),
    'shop' => env('SHOPIFY_SHOP', env('SHOPIFY_API_URL')),
];