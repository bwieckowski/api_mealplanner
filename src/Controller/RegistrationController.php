<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\RegistrationService;
use App\Exception\BadRequestException;

class RegistrationController
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function registerAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestException(['Bad request'], 400);
        }
        $this->registrationService->register($data);
        return new JsonResponse('Created', 201);
    }
}