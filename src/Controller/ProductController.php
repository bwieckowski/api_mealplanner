<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $service)
    {
        $this->productService = $service;
    }

    public function getAll()
    {
        $userId = $this->getUser()->getId();
        $products = $this->productService->getAllByUserId($userId);
        return $this->json($products);
    }

    public function getAllPagination(Request $request)
    {
        $userId = $this->getUser()->getId();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 1);
        $pagination = $this->productService->getAllByUserIdPaginated($userId,$page,$limit);
        $result = [
            'current_page' => $pagination->getCurrentPageNumber(),
            'items' => $pagination->getItems(),
            'page_count' => ceil($pagination->getTotalItemCount()/$pagination->getItemNumberPerPage()),
            'items_per_page' => $pagination->getItemNumberPerPage(),
            'total_item_count' => $pagination->getTotalItemCount()
        ];
        return $this->json($result);
    }

    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $result = $this->productService->create($data,$user);
        return $this->json($result,201);
    }

    public function update($id,Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $this->productService->update($data,$id,$user);
        return $this->json($id);
    }

    public function delete($id)
    {
        $user = $this->getUser();

        $this->productService->delete($id,$user);
        return $this->json($id);
    }

}