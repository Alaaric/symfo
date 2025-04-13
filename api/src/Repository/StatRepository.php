<?php

namespace App\Repository;

use App\Entity\Stat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stat::class);
    }

    public function findTop20ByWeek(string $week): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.week = :week')
            ->setParameter('week', $week)
            ->orderBy('s.views', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getGlobalStats(): array
    {
        return $this->createQueryBuilder('s')
            ->select('IDENTITY(s.image) as imageId, i.name as imageName, SUM(s.views) as totalViews, SUM(s.download) as totalDownloads')
            ->join('s.image', 'i')
            ->groupBy('s.image, i.name')
            ->getQuery()
            ->getResult();
    }

    public function getStatsByColumnOrderAndLimit(string $column, string $order, int $limit, string $week): array
    {
        $qb = $this->createQueryBuilder('s');

        if ($week === 'all') {
            return $qb->select('IDENTITY(s.image) as imageId, i.name as imageName, SUM(s.views) as totalViews, SUM(s.download) as totalDownloads')
                ->join('s.image', 'i') // Jointure avec l'entité Image
                ->groupBy('s.image, i.name')
                ->orderBy('SUM(s.' . $column . ')', $order) // Tri basé sur la somme de la colonne
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }

        return $qb->select('s')
            ->andWhere('s.week = :week')
            ->setParameter('week', $week)
            ->orderBy('s.' . $column, $order)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getAvailableWeeks(): array
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT s.week')
            ->orderBy('s.week', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
