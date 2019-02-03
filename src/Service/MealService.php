<?php

namespace App\Service;

use App\Entity\Meal;
use App\Entity\MealProduct;
use App\Form\MealType;
use App\Repository\MealRepository;
use App\Repository\MealProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
    private $formFactory;

    public function __construct(MealRepository $mealRepository,
                                EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                FormFactoryInterface $formFactory,
                                ProductRepository $productRepository,
                                MealProductRepository $mealProductRepository)
    {
        $this->mealRepository = $mealRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->formFactory = $formFactory;
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
        $newProductsList = $data['products'];
        unset($data['products']);
        $meal = new Meal();
        $form = $this->formFactory->create(MealType::class,$meal);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            throw new ValidationException($errors);
        }
        $meal->setUser($user);

        foreach ($newProductsList as $item) {
            $product = $this->productRepository->getOneById($item['id']);
            $mealProduct = new MealProduct();
            $mealProduct->setProduct($product);
            $mealProduct->setAmount($item['amount']);
            $mealProduct->setMeal($meal);
            $this->em->persist($mealProduct);
        }
        $this->em->persist($meal);
        $this->em->flush();

        return $meal->getId();
    }

    public function update(array $data,$id,User $user)
    {
        $newProductsList = $data['products'];
        unset($data['products']);
        $meal = $this->mealRepository->getOneById($id);
        $productsList = $this->mealProductRepository->getRecordsByMealId($id);
        if (!$meal) {
            throw new BadRequestException('The product not found');
        }

        if ($meal->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The product have not got to you !');
        }

        $form = $this->formFactory->create(MealType::class,$meal);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            throw new ValidationException($errors);
        }

        foreach ($productsList as $record) {
            $this->em->remove($record);
        }
        foreach ($newProductsList as $item) {
            $product = $this->productRepository->getOneById($item['id']);
            $mealProduct = new MealProduct();
            $mealProduct->setProduct($product);
            $mealProduct->setAmount($item['amount']);
            $mealProduct->setMeal($meal);
            $this->em->persist($mealProduct);
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

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}