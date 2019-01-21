<?php

namespace App\Repository;

use App\Entity\PersonDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PersonDetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonDetails::class);
    }

    public function getOneByUserId($id)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function getSelectedDetailsByUserId($id)
    {
        return $this->createQueryBuilder('d')
            ->select('d.id,d.calory,d.sex,d.weight,d.height,d.protein,d.carbon,d.fat')
            ->where('d.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}