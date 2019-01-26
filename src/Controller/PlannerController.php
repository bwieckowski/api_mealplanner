<?php

namespace App\Controller;

use App\Service\PlannerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PlannerController extends AbstractController
{
    private $plannerService;

    public function __construct(PlannerService $plannerService)
    {
        $this->plannerService = $plannerService;
    }

    public function getItemsForWeek(Request $request)
    {
        $userId = $this->getUser()->getId();
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $result = $this->plannerService->getItemsForWeek($startDate,$endDate,$userId);
        return $this->json($result);
    }

    public function addItem(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $result = $this->plannerService->addItem($data,$user);
        return $this->json($result,201);
    }

    public function changePosition($id,Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $this->plannerService->changePosition($data,$id,$user);
        return $this->json($id);
    }

    public function deleteItem($id)
    {
        $user = $this->getUser();

        $this->plannerService->deleteItem($id,$user);
        return $this->json($id);
    }
}