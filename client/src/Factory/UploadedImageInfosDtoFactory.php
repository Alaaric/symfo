<?php

namespace App\Factory;

use App\Dto\UploadedImageInfosDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedImageInfosDtoFactory
{
    public function createFromUploadedFile(UploadedFile $file): UploadedImageInfosDto
    {
        return new UploadedImageInfosDto(
            $file->getPathname(),
            $file->getClientOriginalName()
        );
    }
}