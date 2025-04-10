<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file, string $destination): string
    {
        $uniqueName = uniqid() . '.' . $file->guessExtension();
        $file->move($destination, $uniqueName);

        return $uniqueName;
    }
}