<?php

namespace App\Mapper;

use App\Dto\StatOutputDto;
use App\Entity\Stat;

class StatOutputDtoForTotalMapper
{

    /**
     * @return StatOutputDto[]
     */
    public function mapGlobalStatsToDto(array $globalStats): array
    {
        $dto = [];

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

        return $dto;
    }
}
