<?php

namespace App\Service;

use App\Entity\PersonDetails;
use App\Repository\PersonDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ValidationException;
use App\Exception\BadRequestException;
use App\Entity\User;

class PersonDetailsService
{
    private $detailsRepository;
    private $em;
    private $validator;

    public function __construct(PersonDetailsRepository $detailsRepository,
                                EntityManagerInterface $em,
                                ValidatorInterface $validator)
    {
        $this->detailsRepository = $detailsRepository;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function getDetailsByUserId($userId)
    {
        return $this->detailsRepository->getSelectedDetailsByUserId($userId);
    }

    public function create(array $data, User $user)
    {
        $details = new PersonDetails();
        $details->setSex($data['sex']);
        $details->setWeight($data['weight']);
        $details->setHeight($data['height']);
        $details->setCalory($data['calory']);
        $details->setProtein($data['protein']);
        $details->setCarbon($data['carbon']);
        $details->setFat($data['fat']);
        $details->setUser($user);

        $errors = $this->validator->validate($details);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            throw new ValidationException($messages);
        }

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
        $details->setSex($data['sex']);
        $details->setWeight($data['weight']);
        $details->setHeight($data['height']);
        $details->setCalory($data['calory']);
        $details->setProtein($data['protein']);
        $details->setCarbon($data['carbon']);
        $details->setFat($data['fat']);
        $details->setUser($user);

        $errors = $this->validator->validate($details);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            throw new ValidationException($messages);
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
}