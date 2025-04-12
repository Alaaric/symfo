<?php

namespace App\Dto;

class StatOutputDto
{

    public function __construct(
        public readonly int $id,
        public readonly string $week,
        public readonly ?int $views,
        public readonly ?int $downloads,
        public readonly int $imageId,
        public readonly string $imageName
    ) {}
}
