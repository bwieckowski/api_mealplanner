<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getAllByUserIdQuery($id)
    {
        return $this->createQueryBuilder('p')
            ->select('p.name, p.calory, p.protein, p.carbon, p.fat, p.weight')
            ->orderBy('p.name', 'ASC')
            ->andWhere('p.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ;
    }

    public function searchViaNameAndUserIdQuery($search,$userId)
    {
        return $this->createQueryBuilder('p')
            ->select('p.name, p.calory, p.protein, p.carbon, p.fat, p.weight')
            ->andWhere('p.name LIKE :search')
            ->andWhere('p.user = :userId')
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('userId', $userId)
            ->orderBy('p.name', 'ASC')
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
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function getOneByIdWithSelectedFields($id,$userId)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.name as title')
            ->where('p.id = :id')
            ->andWhere('p.user = :userId')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}