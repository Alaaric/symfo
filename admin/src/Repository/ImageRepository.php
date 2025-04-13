<?php

namespace App\Repository;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageRepository
{
    private HttpClientInterface $httpClient;
    private const string API_URL = 'http://localhost:8002/images/';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getAllImages(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL);
        return $response->toArray();
    }

    public function deleteImage(int $id): array
    {
        $response = $this->httpClient->request('POST', self::API_URL . $id, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 204) {
            throw new \Exception('Failed to delete image');
        }

        return ['message' => 'Image deleted successfully'];
    }
}
