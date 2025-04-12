<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Stat;
use App\Factory\StatFactory;
use Doctrine\ORM\EntityManagerInterface;

class StatsTracker
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StatFactory $statFactory
    ) {}

    public function trackStat(Image $image, string $action): void
    {
        $week = $this->getCurrentWeek();
        $statRepo = $this->entityManager->getRepository(Stat::class);
        $stat = $statRepo->findOneBy(['image' => $image, 'week' => $week]);

        if (!$stat) {

            $stat = match ($action) {
                'view' => $this->statFactory->createWithViewStat($image, $week),
                'download' => $this->statFactory->createWithDownloadStat($image, $week),
                default => throw new \InvalidArgumentException('Invalid action type'),
            };

            $this->entityManager->persist($stat);
        } else {
            match ($action) {
                'view' => $stat->setViews(($stat->getViews() ?? 0) + 1),
                'download' => $stat->setDownload(($stat->getDownload() ?? 0) + 1),
                default => throw new \InvalidArgumentException('Invalid action type'),
            };;
        }

        $this->entityManager->flush();
    }

    private function getCurrentWeek(): string
    {
        return (new \DateTime())->format('o-W');
    }
}
