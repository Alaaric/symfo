<?php

namespace App\Service;

use App\Entity\Image;
use App\Factory\ImageFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    public function __construct(
        private FileUploader $fileUploader,
        private EntityManagerInterface $entityManager,
        private ImageFactory $imageFactory
    ) {
    }

    public function uploadAndSaveImage(UploadedFile $file, string $destination): Image
    {
        $uniqueName = $this->fileUploader->upload($file, $destination);
        $image = $this->imageFactory->create($uniqueName, $file->getClientOriginalName());

        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $image;
    }
}