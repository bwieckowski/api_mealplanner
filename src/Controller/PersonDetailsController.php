<?php

namespace App\Controller;

use App\Service\PersonDetailsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;

class PersonDetailsController extends AbstractController
{
    private $detailsService;

    public function __construct(PersonDetailsService $service)
    {
        $this->detailsService = $service;
    }

    public function getDetailsForUser(SerializerInterface $serializer)
    {
        $userId = $this->getUser()->getId();
        $details = $this->detailsService->getDetailsByUserId($userId);
        return $this->json($details);
    }

    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $result = $this->detailsService->create($data,$user);
        return $this->json($result,201);
    }

    public function update(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $this->detailsService->update($data,$user);
        return $this->json("Changed");
    }

    public function delete()
    {
        $userId = $this->getUser()->getId();

        $this->detailsService->delete($userId);
        return $this->json("Deleted");
    }

}