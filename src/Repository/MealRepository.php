<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MealRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Meal::class);
    }

    public function getAllByUserIdQuery($id)
    {
        return $this->createQueryBuilder('m')
            ->select('m.id, m.name')
            ->andWhere('m.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ;
    }

    public function getAllByUserId($id)
    {
        return $this->getAllByUserIdQuery($id)
            ->getResult();
    }

    public function getOneById($id)
    {
        return $this->find($id);
    }

    public function getOneByIdWithSelectedFields($id,$userId)
    {
        return $this->createQueryBuilder('m')
            ->select('m.id, m.name as title')
            ->where('m.id = :id')
            ->andWhere('m.user = :userId')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}