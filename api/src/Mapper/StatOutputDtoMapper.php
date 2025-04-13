<?php

namespace App\Mapper;

use App\Dto\StatOutputDto;
use App\Entity\Stat;
use App\Mapper\StatOutputDtoForTotalMapper;

class StatOutputDtoMapper
{

    public function __construct(
        private readonly StatOutputDtoForTotalMapper $totalMapper
    ) {}

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
            $dto = array_merge($dto, $this->totalMapper->mapGlobalStatsToDto($globalStats));
        }

        return $dto;
    }
}
