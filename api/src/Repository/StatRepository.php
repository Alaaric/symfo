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
}
