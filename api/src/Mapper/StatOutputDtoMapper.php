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
    public function mapToStatOutputDto(array $stats, ?array $globalStats = null): array
    {
        $dto = array_map(function ($stat) {
            return new StatOutputDto(
                $stat->getId(),
                $stat->getWeek(),
                $stat->getViews(),
                $stat->getDownload(),
                $stat->getImage()->getId(),
                $stat->getImage()->getName()
            );
        }, $stats);

        if ($globalStats !== null) {
            foreach ($globalStats as $globalStat) {
                $dto[] = new StatOutputDto(
                    0,
                    'all',
                    $globalStat['totalViews'],
                    $globalStat['totalDownloads'],
                    $globalStat['imageId'],
                    $globalStat['imageName']
                );
            }
        }



        return $dto;
    }
}
