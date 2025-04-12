<?php

namespace App\Service;

use App\Repository\ImageRepository;

class ImageDownloadService
{
    public function __construct(private ImageRepository $imageRepository) {}

    public function getDownloadData(int $id): array
    {
        $response = $this->imageRepository->downloadImageById($id);

        $disposition = $response->getHeaders()['content-disposition'][0] ?? null;
        preg_match('/filename="(.+?)"/', $disposition, $matches);
        $filename = $matches[1] ?? 'default.jpg';

        return [
            'content' => $response->getContent(),
            'filename' => $filename,
            'contentType' => $response->getHeaders()['content-type'][0] ?? 'application/octet-stream',
        ];
    }
}
