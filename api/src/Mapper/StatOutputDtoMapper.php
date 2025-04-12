<?php

namespace App\Mapper;

use App\Dto\StatOutputDto;
use App\Entity\Stat;

class StatOutputDtoMapper
{

    /**
     * @param Stat[] $stats
     * @return StatOutputDto[]
     */
    public function mapToStatOutputDto(array $stats): array
    {
        return array_map(function ($stat) {
            return new StatOutputDto(
                $stat->getId(),
                $stat->getWeek(),
                $stat->getViews(),
                $stat->getDownload(),
                $stat->getImage()->getId(),
                $stat->getImage()->getName()
            );
        }, $stats);
    }


}