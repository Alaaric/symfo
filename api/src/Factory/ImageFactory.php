<?php

namespace App\Factory;

use App\Entity\Image;

class ImageFactory
{
    public function create(string $fileName, ?string $originalName = null): Image
    {
        $image = new Image();
        $image->setFileName($fileName);
        $image->setName($originalName);

        return $image;
    }
}