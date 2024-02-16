<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApiLoginController extends AbstractController
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }
    
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user, UserRepository $userRepository, Request $request, JWTTokenManagerInterface $jwtManager): Response
    {

        if (null === $user) {
            return $this->json([
                'message' => 'identifiants incorrectes',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
