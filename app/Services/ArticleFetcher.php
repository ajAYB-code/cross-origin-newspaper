<?php

namespace App\Services;

use GuzzleHttp\Client;

class ArticleFetcher
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('API_BASE_URL'), // URL de base Swagger
        ]);
    }

    public function fetchLeMondeArticles()
    {
        $response = $this->client->get('/lemonde');
        return json_decode($response->getBody(), true);
    }
}
