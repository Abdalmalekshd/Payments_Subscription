<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Signfly\Shopify\Exceptions\ShopifyException;
class ShopifyController extends Controller
{
    protected $client;
    protected $apiKey;
    protected $apiSecret;
    protected $redirectUri;
    protected $scopes;
    protected $shop;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('shopify.api_key');
        $this->apiSecret = config('shopify.api_secret');
        $this->redirectUri = config('shopify.redirect_uri');
        $this->scopes = config('shopify.scopes');
        $this->shop = config('shopify.shop');
    }

    public function redirectToShopify()
    {
        $authUrl = "https://{$this->shop}/admin/oauth/authorize?client_id={$this->apiKey}&scope={$this->scopes}&redirect_uri={$this->redirectUri}";
        return redirect()->away($authUrl);
    }

    public function handleShopifyCallback(Request $request)
    {
        $code = $request->get('code');
        $shop = $request->get('shop');

        try {
            $response = $this->client->post("https://{$shop}/admin/oauth/access_token", [
                'form_params' => [
                    'client_id' => $this->apiKey,
                    'client_secret' => $this->apiSecret,
                    'code' => $code,
                ],
            ]);

            $accessToken = json_decode($response->getBody()->getContents(), true)['access_token'];
            // Save access token and shop info to the database or session
            // $this->saveAccessToken($shop, $accessToken);

            return redirect()->route('home');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}