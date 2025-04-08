<?php

namespace App\Repository;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiRepository
{
    private HttpClientInterface $httpClient;
    private const string API_URL = 'http://localhost:8002/';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchTestData(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL . 'test');
        return $response->toArray();
    }
}