<?php

namespace App\Service;

use App\Entity\PlannerItem;
use App\Repository\MealRepository;
use App\Repository\PlannerItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\AccessDeniedException;
use App\Entity\User;

class PlannerService
{
    private $itemsRepository;
    private $mealRepository;
    private $em;

    public function __construct(PlannerItemRepository $itemsRepository,
                                MealRepository $mealRepository,
                                EntityManagerInterface $em)
    {
        $this->itemsRepository = $itemsRepository;
        $this->mealRepository = $mealRepository;
        $this->em = $em;
    }


    public function getItemsForWeek($startDate,$endDate,$userId)
    {
        return $this->itemsRepository->getItemsBetweenDates($startDate,$endDate,$userId);
    }

    public function addItem(array $data, User $user)
    {
        $meal = $this->mealRepository->getOneById($data['meal']);
        $date = \DateTime::createFromFormat('Y-m-d H:i:s',$data['date']);
        $plannerItem = new PlannerItem();
        $plannerItem->setMeal($meal);
        $plannerItem->setDate($date);
        $plannerItem->setColor($data['color']);
        $plannerItem->setPosition($data['position']);
        $plannerItem->setUser($user);

        $this->em->persist($plannerItem);
        $this->em->flush();

        return $plannerItem->getId();
    }

    public function changePosition($data,$id,$user)
    {
        $item = $this->itemsRepository->getOneById($id);
        if ($item->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The item have not got to you !');
        }
        $item->setPosition($data['position']);
        $this->em->flush();
    }

    public function deleteItem($id,$user)
    {
        $item = $this->itemsRepository->getOneById($id);
        if ($item->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('The item have not got to you !');
        }
        $this->em->remove($item);
        $this->em->flush();
    }
}