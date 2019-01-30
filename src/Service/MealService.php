<?php

namespace App\Service;

use App\Entity\Meal;
use App\Entity\MealProduct;
use App\Repository\MealRepository;
use App\Repository\MealProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Exception\BadRequestException;
use App\Exception\AccessDeniedException;
use App\Exception\ValidationException;

class MealService
{
    private $mealRepository;
    private $productRepository;
    private $mealProductRepository;
    private $em;
    private $paginator;
    private $validator;

    public function __construct(MealRepository $mealRepository,
                                EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                ValidatorInterface $validator,
                                ProductRepository $productRepository,
                                MealProductRepository $mealProductRepository)
    {
        $this->mealRepository = $mealRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->mealProductRepository = $mealProductRepository;
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

    public function getViaNamePaginated($search,$userId,$page,$limit)
    {
        $query = $this->mealRepository->SearchViaNameAndUserIdQuery($search,$userId);
        return $this->paginator->paginate($query,$page,$limit);
    }

    public function getOne($id,$user)
    {
        $meal =  $this->mealRepository->getOneByIdWithSelectedFields($id,$user->getId());
        if (!$meal) {
            throw new BadRequestException('The meal not found');
        }

        $productsList = ["products" => $this->mealProductRepository->getProductsList($id)];
        $result = array_merge($productsList, $meal);

        return $result;
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

        foreach ($data['products'] as $item) {
            $product = $this->productRepository->getOneById($item['id']);
            $mealProduct = new MealProduct();
            $mealProduct->setProduct($product);
            $mealProduct->setAmount($item['amount']);
            $mealProduct->setMeal($meal);
            $this->em->persist($mealProduct);
        }
        $errors = $this->validator->validate($meal);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            throw new ValidationException($messages);
        }

        $this->em->persist($meal);
        $this->em->flush();

        return $meal->getId();
    }

    public function update(array $data,$id,User $user)
    {
        $meal = $this->mealRepository->getOneById($id);
        $mealProducts = $this->mealProductRepository->getRecordsByMealId($id);
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

        foreach ($data['products'] as $item) {
            $product = $this->productRepository->getOneById($item['id']);
            foreach ($mealProducts as $record) {
                $record->setProduct($product);
                $record->setAmount($item['amount']);
                $record->setMeal($meal);
                $this->em->persist($record);
            }
        }
        $errors = $this->validator->validate($meal);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            throw new ValidationException($messages);
        }

        $this->em->flush();
    }

    public function delete($id,User $user)
    {
        $meal = $this->mealRepository->getOneById($id);
        $mealProduct = $this->mealProductRepository->getRecordsByMealId($id);
        if (!$meal) {
            throw new BadRequestException('The product not found');
        }

        if ($meal->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The product have not got to you !');
        }
        foreach ($mealProduct as $record) {
            $this->em->remove($record);
        }
        $this->em->remove($meal);
        $this->em->flush();
    }
}