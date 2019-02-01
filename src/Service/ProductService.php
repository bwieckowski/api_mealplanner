<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\BadRequestException;
use App\Exception\AccessDeniedException;

class ProductService
{
    private $productRepository;
    private $em;
    private $paginator;
    private $formFactory;

    public function __construct(ProductRepository $productRepository,
                                EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                FormFactoryInterface $formFactory)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->formFactory = $formFactory;
    }

    public function getAllByUserId($userId)
    {
        return $this->productRepository->getAllByUserId($userId);
    }

    public function getAllByUserIdPaginated($userId,$page,$limit)
    {
        $query = $this->productRepository->getAllByUserIdQuery($userId);
        return $this->paginator->paginate($query,$page,$limit);
    }

    public function getViaNamePaginated($search,$userId,$page,$limit)
    {
        $query = $this->productRepository->searchViaNameAndUserIdQuery($search,$userId);
        return $this->paginator->paginate($query,$page,$limit);
    }

    public function getOne($id,$userId)
    {
        return $this->productRepository->getOneByIdWithSelectedFields($id,$userId);
    }

    public function create(array $data, User $user)
    {
        $product = new Product();
        $form = $this->formFactory->create(ProductType::class,$product);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            throw new ValidationException($errors);
        }
        $product->setUser($user);
        $this->em->persist($product);
        $this->em->flush();

        return $product->getId();
    }

    public function update(array $data,$id,User $user)
    {
        $product = $this->productRepository->getOneById($id);
        if (!$product) {
            throw new BadRequestException('The product not found');
        }

        if ($product->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The product have not got to you !');
        }

        $form = $this->formFactory->create(ProductType::class,$product);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            throw new ValidationException($errors);
        }
        $this->em->flush();
    }

    public function delete($id,User $user)
    {
        $product = $this->productRepository->getOneById($id);
        if (!$product) {
            throw new BadRequestException('The product not found');
        }

        if ($product->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The product have not got to you !');
        }

        $this->em->remove($product);
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