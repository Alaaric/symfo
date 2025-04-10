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

    public function trackImageView(Image $image): void
    {
        $week = $this->getCurrentWeek();
        $statRepo = $this->entityManager->getRepository(Stat::class);
        $stat = $statRepo->findOneBy(['image' => $image, 'week' => $week]);

        if (!$stat) {

            $stat = $this->statFactory->create($image, $week);
            $this->entityManager->persist($stat);

        } else {

            $stat->setViews($stat->getViews() + 1);
            
        }

        $this->entityManager->flush();
    }

    private function getCurrentWeek(): string
    {
        return (new \DateTime())->format('o-\WW');
    }
}