<?php

namespace App\Repository;

use App\Dto\StatDto;
use App\Mapper\StatMapper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StatRepository
{
    private const string API_URL = 'http://localhost:8002/stats';

    public function __construct(private HttpClientInterface $httpClient) {}

    public function getAllStats(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL);

        return $response->toArray();
    }
}