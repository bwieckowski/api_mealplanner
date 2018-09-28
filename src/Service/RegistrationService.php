<?php

namespace App\Service;

use App\Exception\ValidationException;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\Form\FormInterface;

class RegistrationService
{
    private $formFactory;
    private $userManager;

    public function __construct(FactoryInterface $formFactory,
                                UserManagerInterface $userManager)
    {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
    }

    public function register($data)
    {
        $user = $this->userManager->createUser();
        $form = $this->formFactory->createForm(['csrf_protection' => false]);
        $form->setData($user);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = json_encode($this->getErrorsFromForm($form));
            throw new ValidationException($errors);
        }
        $this->userManager->updateUser($user);
    }
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
