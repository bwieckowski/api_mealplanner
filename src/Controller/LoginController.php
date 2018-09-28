<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class LoginController
{
    protected $em;
    protected $passwordEncoder;
    protected $jwtEncoder;

    public function __construct(EntityManagerInterface $em,
                                JWTEncoderInterface $jwtEncoder,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
    }

    public function loginAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['username' => $data['username']]);

        if (!$user) {
            throw new BadCredentialsException();
        }

        $isValid = $this->passwordEncoder->isPasswordValid($user,$data['password']);

        if (!$isValid) {
            throw new BadCredentialsException("Bad credentials");
        }
        $token = $this->jwtEncoder->encode(['username' => $data['username'],'exp' => time() + 3600]);

        return new JsonResponse(['token' => $token],200);

    }
}