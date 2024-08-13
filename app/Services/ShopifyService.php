<?php

namespace App\Services;

use GuzzleHttp\Client;

class ShopifyService
{
    protected $client;
    protected $apiKey;
    protected $apiSecret;
    protected $storeUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('SHOPIFY_API_KEY');
        $this->apiSecret = env('SHOPIFY_API_SECRET');
        $this->storeUrl = env('SHOPIFY_STORE_URL');
    }

    public function get($endpoint)
    {
        $url = "https://{$this->storeUrl}/admin/api/2023-01/{$endpoint}.json";
        $response = $this->client->get($url, [
            'headers' => [
                'X-Shopify-Access-Token' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function post($endpoint, $data)
    {
        $url = "https://{$this->storeUrl}/admin/api/2023-01/{$endpoint}.json";
        $response = $this->client->post($url, [
            'headers' => [
                'X-Shopify-Access-Token' => $this->apiKey,
            ],
            'json' => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}