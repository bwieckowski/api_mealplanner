<?php

namespace App\Service;

use App\Entity\PersonDetails;
use App\Form\PersonDetailsType;
use App\Repository\PersonDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Exception\ValidationException;
use App\Exception\BadRequestException;
use App\Entity\User;

class PersonDetailsService
{
    private $detailsRepository;
    private $em;
    private $formFactory;

    public function __construct(PersonDetailsRepository $detailsRepository,
                                EntityManagerInterface $em,
                                FormFactoryInterface $formFactory)
    {
        $this->detailsRepository = $detailsRepository;
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function getDetailsByUserId($userId)
    {
        return $this->detailsRepository->getSelectedDetailsByUserId($userId);
    }

    public function create(array $data, User $user)
    {
        $details = $this->detailsRepository->getOneByUserId($user->getId());
        if ($details) {
            throw new BadRequestException('Details are exist');
        }
        $details = new PersonDetails();
        $form = $this->formFactory->create(PersonDetailsType::class,$details);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            throw new ValidationException($errors);
        }
        $details->setUser($user);

        $this->em->persist($details);
        $this->em->flush();

        return $details->getId();
    }

    public function update(array $data, User $user)
    {
        $details = $this->detailsRepository->getOneByUserId($user->getId());
        if (!$details) {
            throw new BadRequestException('Details not found');
        }

        $form = $this->formFactory->create(PersonDetailsType::class,$details);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            throw new ValidationException($errors);
        }
        $this->em->persist($details);
        $this->em->flush();

        return $details->getId();
    }

    public function delete($userId)
    {
        $details = $this->detailsRepository->getOneByUserId($userId);
        if (!$details) {
            throw new BadRequestException('Details not found');
        }

        $this->em->remove($details);
        $this->em->flush();
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
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