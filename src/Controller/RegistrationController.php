<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('api/register', name: 'app_register',methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $userRequest = $serializer->deserialize($request->getContent(), User::class, 'json');

        $user = new User();
        $user->setNickname($userRequest->getNickname());
        $user->setEmail($userRequest->getEmail());
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($userPasswordHasher->hashPassword($user, $userRequest->getPassword()));

        $violations = $validator->validate($user);

        if (0 !== count($violations)) {
            return $this->json([$violations, 500, ['message' => 'error']]);
            } else {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([$user,'message' => 'create successful'], 201);
        }
    }
}
