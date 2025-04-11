<?php

namespace App\Repository;

use App\Dto\UploadedImageInfosDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageRepository
{
    private HttpClientInterface $httpClient;
    private const string API_URL = 'http://localhost:8002/';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getAllImages(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL . 'images');
        return $response->toArray();
    }

    public function getImageById(int $id): string
    {
        $response = $this->httpClient->request('GET', self::API_URL . 'images/' . $id . '/file');
  
        return $response->getContent();
    }

    public function uploadImage(UploadedImageInfosDto $dto): array
    {
        $response = $this->httpClient->request('POST', self::API_URL . 'images/new', [
            'headers' => [
                'Content-Type' => 'multipart/form-data',
            ],
            'body' => [
                'image' => fopen($dto->filePath, 'r'),
                'originalName' => $dto->originalName,
            ],
        ]);
    
        return $response->toArray();
    }
}