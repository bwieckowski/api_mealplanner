<?php

namespace App\Repository;

use App\Entity\MealProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MealProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MealProduct::class);
    }

    public function getProductsList($id)
    {
        return $this->createQueryBuilder('e')
            ->select('e.amount,p.name')
            ->innerJoin('e.product', 'p')
            ->where('e.meal = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function getRecordsByMealId($mealId)
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.meal = :mealId')
            ->setParameter('mealId', $mealId)
            ->getQuery()
            ->getResult();
    }
}