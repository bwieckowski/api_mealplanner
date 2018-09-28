<?php

namespace App\Controller;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\RegistrationService;

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
            throw new ValidationException('Bad request');
        }
        $this->registrationService->register($data);
        return new JsonResponse('Created', 201);
    }
}