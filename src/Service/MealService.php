<?php

namespace App\Service;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Exception\BadRequestException;
use App\Exception\AccessDeniedException;

class MealService
{
    private $mealRepository;
    private $productRepository;
    private $em;
    private $paginator;
    private $validator;

    public function __construct(MealRepository $mealRepository,
                                EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                ValidatorInterface $validator,
                                ProductRepository $productRepository)
    {
        $this->mealRepository = $mealRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->productRepository = $productRepository;
    }

    public function getAllByUserId($userId)
    {
        return $this->mealRepository->getAllByUserId($userId);
    }

    public function getAllByUserIdPaginated($userId,$page,$limit)
    {
        $query = $this->mealRepository->getAllByUserIdQuery($userId);
        return $this->paginator->paginate($query,$page,$limit);
    }

    public function getOne($id)
    {
        return $this->mealRepository->getOneById($id);
    }

    public function create(array $data, User $user)
    {
        $meal = new Meal();
        $meal->setName($data['name']);
        $meal->setDescription($data['description']);
        $meal->setPortions($data['portions']);
        $meal->setCalory($data['calory']);
        $meal->setProtein($data['protein']);
        $meal->setCarbon($data['carbon']);
        $meal->setFat($data['fat']);
        $meal->setWeight($data['weight']);
        $meal->setUser($user);
        foreach ($data['products'] as &$id) {
            $product = $this->productRepository->getOneById($id);
            $meal->addProduct($product);
        }

        $this->em->persist($meal);
        $this->em->flush();

        return $meal->getId();
    }

    public function update(array $data,$id,User $user)
    {
        $meal = $this->mealRepository->getOneById($id);
        if (!$meal) {
            throw new BadRequestException('The product not found');
        }

        if ($meal->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The product have not got to you !');
        }

        $meal->setName($data['name']);
        $meal->setDescription($data['description']);
        $meal->setPortions($data['portions']);
        $meal->setCalory($data['calory']);
        $meal->setProtein($data['protein']);
        $meal->setCarbon($data['carbon']);
        $meal->setFat($data['fat']);
        $meal->setWeight($data['weight']);
        $meal->setUser($user);
        foreach ($data['products'] as &$id) {
            $product = $this->productRepository->getOneById($id);
            $meal->addProduct($product);
        }

        $this->em->flush();
    }

    public function delete($id,User $user)
    {
        $meal = $this->mealRepository->getOneById($id);
        if (!$meal) {
            throw new BadRequestException('The product not found');
        }

        if ($meal->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The product have not got to you !');
        }

        $this->em->remove($meal);
        $this->em->flush();
    }
}