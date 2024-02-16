<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiLoginController extends AbstractController
{
    
    #[Route('/login_info', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {

        if (null === $user) {
            return $this->json([
                'message' => 'identifiants incorrectes',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id'  => $user->getId(),
            'nickname'  => $user->getNickname(),
            'email'  => $user->getEmail(),
            'roles'  => $user->getRoles(),
            'password'  => $user->getPassword(),
            'description'  => $user->getDescription(),
            'picture'  => $user->getPicture()
        ]);
    }
}
