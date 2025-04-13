<?php

namespace App\Repository;

use App\Dto\UploadedImageInfosDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ImageRepository
{
    private HttpClientInterface $httpClient;
    private const string API_URL = 'http://localhost:8002/images/';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getImageFileForDisplay(string $imgName): string
    {
        $response = $this->httpClient->request('GET', "http://localhost:8002/uploads/images/$imgName");

        return $response->getContent();
    }
    public function getAllImages(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL);
        return $response->toArray();
    }

    public function getImageById(int $id): string
    {
        $response = $this->httpClient->request('GET', self::API_URL . $id . '/file');

        return $response->getContent();
    }

    public function downloadImageById(int $id): ResponseInterface
    {
        return $this->httpClient->request('GET', self::API_URL . 'download/' . $id);
    }

    public function uploadImage(UploadedImageInfosDto $dto): array
    {
        $response = $this->httpClient->request('POST', self::API_URL . 'new', [
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
