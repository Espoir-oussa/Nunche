<?php

namespace App\Services;

use GuzzleHttp\Client;

class FedaPayApiService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://sandbox-api.fedapay.com/v1';
        $this->apiKey = config('services.fedapay.secret_key');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }


    public function createTransaction($data)
    {
        $response = $this->client->post('/v1/transactions', [
            'json' => $data
        ]);
        return json_decode($response->getBody(), true);
    }
}
