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
            ->select('m.name as title,m.calory')
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
        return $this->createQueryBuilder('m')
            ->leftJoin('m.products', 'p')
            ->select('m','p')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}