<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Comment;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

        // return json
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

    /**
     * show one user by its id
     *
     * @return Response
     */
    #[Route('/user/{id}', name: 'app_user_show',methods: ['GET'])]
    public function show(User $user): Response
    {
        // check if $user doesn't exist
        if (!$user) {
            return $this->json(
                "Error : User inexistant",
                // status code
                404
            );
        }

        // return json
        return $this->json(
            // what I want to show
            $user,
            // status code
            200,
            // header
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_user']
        );
    }

    /**
     * show one user by its id
     *
     * @return Response
     */
    #[Route('/user', name: 'app_user_create',methods: ['POST'])]
    public function create(EntityManagerInterface $manager): Response
    {
        $user = new User();
        // set
        $user->setEmail('testApidohijsdfùgh@test.com');
        $user->setNickname('test');
        $user->setDescription('Cheesecake macaroni cheese melted cheese. Cheese strings macaroni cheese cheesecake say cheese manchego airedale squirty cheese parmesan. Cheese and wine goat roquefort squirty cheese melted cheese who moved my cheese emmental mascarpone. Feta cheese strings danish fontina.
        Cheese and biscuits edam cauliflower cheese. Chalk and cheese the big cheese airedale monterey jack cottage cheese fromage frais cow say cheese. Halloumi manchego boursin red leicester say cheese roquefort dolcelatte parmesan. Paneer cheese triangles fondue edam lancashire.');
        $user->setPicture('https://img.colleconline.com/imgdescription/06af8fba638441b0921770665abc0915/ae0fa799-3150-45ed-9f57-f3831d30e6ee.jpg');
        $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        // record in database
        $manager->persist($user);
        $manager->flush();

        return $this->json([201, ['message' => 'create successful']]);
    }

        /**
    * update one user
    *
    * @param UserRepository $userRepository
    * @return Response
    */
    #[Route('/user/{id}', name: 'api_user_update',methods: ['PUT'])]
    public function update(User $user = null, EntityManagerInterface $entityManager): Response
    {
        // check if $user doesn't exist
        if (!$user) {
            return $this->json(
                "Error : User inexistant",
                // status code
                404
            );
        }

        $user->setEmail('testzrtyhzrthszrthsrgfhdfg@test.com');
        $user->setNickname('test');
        $user->setDescription('Cheesecake macaroni cheese melted cheese. Cheese strings macaroni cheese cheesecake say cheese manchego airedale squirty cheese parmesan. Cheese and wine goat roquefort squirty cheese melted cheese who moved my cheese emmental mascarpone. Feta cheese strings danish fontina.
        Cheese and biscuits edam cauliflower cheese. Chalk and cheese the big cheese airedale monterey jack cottage cheese fromage frais cow say cheese. Halloumi manchego boursin red leicester say cheese roquefort dolcelatte parmesan. Paneer cheese triangles fondue edam lancashire.');
        $user->setPicture('https://img.colleconline.com/imgdescription/06af8fba638441b0921770665abc0915/ae0fa799-3150-45ed-9f57-f3831d30e6ee.jpg');
        $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);

        $entityManager->flush();
 
        return $this->json(['message' => 'updated successful', 200]);
 
    }

    /**
    * delete one user
    * 
    * @param UserRepository $userRepository
    * @return Response
    */
    #[Route('/user/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(User $user = null , EntityManagerInterface $entityManager): Response
    {
         // check if $user doesn't exist
        if (!$user) {
            return $this->json(
                ['message' => 'User inexistant'],
                404,
                );
        }
        // delete 
        $entityManager->remove($user);
        // record in database
        $entityManager->flush();

        return $this->json(['message' => 'delete successful', 200]);
       
    }
}
