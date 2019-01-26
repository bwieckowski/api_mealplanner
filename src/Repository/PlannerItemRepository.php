<?php

namespace App\Repository;

use App\Entity\PlannerItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PlannerItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlannerItem::class);
    }

    public function getItemsBetweenDates($startDate,$endDate,$userId)
    {
        return $this->createQueryBuilder('i')
            ->select('i.date, i.color, i.position, m.name as title')
            ->innerJoin('i.meal', 'm')
            ->where('i.date BETWEEN :date1 AND :date2')
            ->andWhere('m.user = :userId')
            ->setParameter('date1', $startDate)
            ->setParameter('date2', $endDate)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getOneById($id)
    {
        return $this->createQueryBuilder('i')
            ->select('i')
            ->where('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}