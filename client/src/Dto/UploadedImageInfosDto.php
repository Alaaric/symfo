<?php

namespace App\Dto;

class UploadedImageInfosDto
{
    public function __construct(
        public readonly string $filePath,
        public readonly string $originalName
    ) {}
}