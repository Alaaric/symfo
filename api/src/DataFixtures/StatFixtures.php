<?php

namespace App\DataFixtures;

use App\Entity\Stat;
use App\Repository\ImageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatFixtures extends Fixture
{
    public function __construct(private ImageRepository $imageRepository) {}

    public function load(ObjectManager $manager): void
    {
        $images = $this->imageRepository->findAll();

        $weeks = [
            '2025-10',
            '2025-11',
            '2025-12',
            '2025-13',
            '2025-14',
            '2025-15',
            '2025-16',
            '2025-17',
        ];

        foreach ($images as $image) {
            foreach ($weeks as $week) {
                $stat = new Stat();
                $stat->setImage($image);
                $stat->setWeek($week);
                $stat->setViews(rand(0, 500));
                $stat->setDownload(rand(0, 100));

                $manager->persist($stat);
            }
        }

        $manager->flush();
    }
}
