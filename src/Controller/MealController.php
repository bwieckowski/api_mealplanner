<?php

namespace App\Controller;

use App\Service\MealService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Factory\PaginationResponseFactory;

class MealController extends AbstractController
{
    private $mealService;

    public function __construct(MealService $service)
    {
        $this->mealService = $service;
    }

    public function getAllPaginated(Request $request)
    {
        $userId = $this->getUser()->getId();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 2);
        $pagination = $this->mealService->getAllByUserIdPaginated($userId,$page,$limit);
        $factory = new PaginationResponseFactory();
        return $factory->create($pagination);
    }

    public function getViaNamePaginated(Request $request)
    {
        $userId = $this->getUser()->getId();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 2);
        $search = $request->query->getAlpha('q');
        $products = $this->mealService->getViaNamePaginated($search,$userId,$page,$limit);

        $factory = new PaginationResponseFactory();
        return $factory->create($products);

    }

    public function getOne($id)
    {
        $user = $this->getUser();
        $meal = $this->mealService->getOne($id,$user);
        return $this->json($meal);
    }

    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $result = $this->mealService->create($data,$user);
        return $this->json($result,201);
    }

    public function update($id,Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $this->mealService->update($data,$id,$user);
        return $this->json($id);
    }

    public function delete($id)
    {
        $user = $this->getUser();

        $this->mealService->delete($id,$user);
        return $this->json($id);
    }
}