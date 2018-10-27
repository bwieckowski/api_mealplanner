<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\BadRequestException;
use App\Exception\DeniedException;

class ProductService
{
    private $productRepository;
    private $em;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    public function getAllByUserId($id)
    {
        return $this->productRepository->getAllByUserId($id);
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

        $this->em->persist($product);
        $this->em->flush();

        return $product->getId();
    }

    public function update(array $data,$id,User $user)
    {
        $product = $this->productRepository->getOneById($id);
        if (!$product instanceof Product) {
            throw new BadRequestException('The product not found');
        }

        if ($product->getUser()->getId() != $user->getId()) {
            throw new DeniedException('The product have not got to you !');
        }

        $product->setName($data['name']);
        $product->setCalory($data['calory']);
        $product->setProtein($data['protein']);
        $product->setCarbon($data['carbon']);
        $product->setFat($data['fat']);
        $product->setWeight($data['weight']);

        $this->em->flush();
    }

    public function delete($id,User $user)
    {
        $product = $this->productRepository->getOneById($id);
        if (!$product instanceof Product) {
            throw new BadRequestException('The product not found');
        }

        if ($product->getUser()->getId() != $user->getId()) {
            throw new DeniedException('The product have not got to you !');
        }

        $this->em->remove($product);
        $this->em->flush();
    }


}