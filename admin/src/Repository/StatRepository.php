<?php

namespace App\Repository;

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

    public function triggerWeeklyReport(): void
    {
        $this->httpClient->request('POST', self::API_URL . '/send-weekly-stats');
    }
}
