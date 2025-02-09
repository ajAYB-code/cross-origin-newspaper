<?php

namespace App\Services;

use GuzzleHttp\Client;

class ArticleFetcher
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('API_BASE_URL'),
        ]);
    }

    public function fetchLeMondeArticles()
    {
        $response = $this->client->get('/lemonde');
        return json_decode($response->getBody(), true);
    }

    public function fetchLequipeArticles()
    {
        $response = $this->client->get('/lequipe', [
            'query' => [
                'token' => env('LEQUIPE_API_TOKEN'),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchLeParisienArticles()
    {
        $response = $this->client->get('/leparisien', [
            'headers' => [
                'Authorization' => 'ApiToken ' . env('PARISIEN_API_TOKEN'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function fetchLiberationArticles($page = 1, $sort = 'asc')
    {
        $response = $this->client->get('/liberation', [
            'query' => [
                'page' => $page,
                'acs' => $sort, 
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . env('LIBERATION_API_TOKEN'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
