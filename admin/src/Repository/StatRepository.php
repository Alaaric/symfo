<?php

namespace App\Repository;

use Symfony\Component\HttpFoundation\Response;
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

    public function getCustomStats(string $column, string $order, int $limit, ?string $week = null): array
    {
        $queryParams = [
            'column' => $column,
            'order' => $order,
            'limit' => $limit,
        ];

        if ($week !== null) {
            $queryParams['week'] = $week;
        }

        $response = $this->httpClient->request('GET', self::API_URL . '/custom?' .  http_build_query($queryParams), [
            'query' => $queryParams,
        ]);

        return $response->toArray();
    }

    public function getAvailableWeeks(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL . '/available-weeks');

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \RuntimeException('Erreur lors de la récupération des semaines disponibles.');
        }

        return $response->toArray();
    }

    public function triggerWeeklyReport(): void
    {
        $this->httpClient->request('POST', self::API_URL . '/send-weekly-stats');
    }
}
