<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\BadRequestException;
use App\Exception\AccessDeniedException;

class ProductService
{
    private $productRepository;
    private $em;
    private $paginator;
    private $validator;

    public function __construct(ProductRepository $productRepository,
                                EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                ValidatorInterface $validator)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->validator = $validator;
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

    public function getOne($id,$userId)
    {
        return $this->productRepository->getOneByIdWithSelectedFields($id,$userId);
    }

    public function create(array $data, User $user)
    {
        $product = new Product();
        $product->setName($data['name']);
        $product->setCalory($data['calory']);
        $product->setProtein($data['protein']);
        $product->setCarbon($data['carbon']);
        $product->setFat($data['fat']);
        $product->setWeight($data['weight']);
        $product->setUser($user);
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            throw new ValidationException($messages);
        }

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

        $product->setName($data['name']);
        $product->setCalory($data['calory']);
        $product->setProtein($data['protein']);
        $product->setCarbon($data['carbon']);
        $product->setFat($data['fat']);
        $product->setWeight($data['weight']);
        $errors = $this->validator->validate($product);
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


}