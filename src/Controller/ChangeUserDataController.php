<?php

namespace App\Controller;

use App\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChangeUserDataController extends AbstractController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function changeUserDataAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();
        if ($data === null) {
            throw new BadRequestException;
        }
        $this->userService->changeUserData($data,$user);
        return new JsonResponse('changed', 200);
    }
}