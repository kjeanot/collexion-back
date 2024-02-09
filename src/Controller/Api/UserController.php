<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// root URL for all routes from MyCollectionController
#[Route('/api')]
class UserController extends AbstractController
{
    /**
     * list all users
     *
     * @return Response
     */
    #[Route('/users', name: 'app_user_list',methods: ['GET'])]
    public function list(UserRepository $userRepository): Response
    {
        // retrieve all users
        $users = $userRepository->findAll();

        // check if $user doesn't exist
        if (!$users) {
            return $this->json(
                "Error : User inexistant",
                // status code
                404
            );
        }

        // retur json
        return $this->json(
            // what I want to show
            $users,
            // status code
            200,
            // header
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_users']
        );
    }
}
