<?php

namespace App\Controller\Api;

use App\Entity\MyCollection;
use App\Entity\User;
use App\Form\MyCollectionType;
use App\Repository\MyCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// root URL for all routes from MyCollectionController
#[Route('/api')]
class MyCollectionController extends AbstractController
{
    /**
     * list all collections
     *
     * @param MyCollectionRepository $myCollectionRepository
     * @return Response
     */
    #[Route('/collections', name: 'api_my_collection_list',methods: ['GET'])]
    public function list(MyCollectionRepository $myCollectionRepository): Response
    {
        // retrieve all collections
        $collections = $myCollectionRepository->findAll();
        
        // check if $myCollection doesn't exist
        if (!$collections) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }

        // return json
        return $this->json(
            // what I want to show
            $collections,
            // status code
            200,
            // header
            [],
            // groups authorized
            ['groups' => 'get_collections']
        );
    }

    /**
     * list one collection by its id
     *
     * @param MyCollectionRepository $myCollectionRepository
     * @return Response
     */
    #[Route('/collection/{id}', name: 'api_my_collection_show',methods: ['GET'])]
    public function show(MyCollection $myCollection): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }
        // return json
        return $this->json(
            // what I want to show
            $myCollection,
            // status code
            200,
            // header
            [],
            // groups authorized
            ['groups' => 'get_collections']
            );
    }

    /**
     * create one collection 
     *
     * @param MyCollectionRepository $myCollectionRepository
     * @return Response
     */
    #[Route('/collection/create', name: 'api_my_collection_create',methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        // // serialize
        // $data = json_decode($request->getContent());
        // // set up a MyCollection entity
        // $myCollection = new MyCollection();
        // // create a form bounded to MyCollection entity
        // $form = $this->createForm(MyCollectionType::class, $myCollection);
        // // send request to form
        // $form->handleRequest($request);
        // // check if the form is submitted and valid
        // if ($form->isSubmitted() && $form->isValid()) {
        //     // record in bdd
        //     $entityManager->persist($myCollection);
        //     $entityManager->flush();

        $user = new User();
            $user->setEmail('usertest@user.com');
            $user->setNickname('usertest');
            $user->setPassword(password_hash('usertest', PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);

        $collection = new MyCollection();
        $collection->setUser($user);
        $collection->setName("Name");
        $collection->setImage("Image");
        $collection->setDescription("Description");
        $collection->setRating(3);
        $collection->setCreatedAt();
        
        $entityManager->persist($collection);
        $entityManager->flush();

    }
    /*
    ROUTE A FAIRE
    #[Route('/collection/random', name: 'api_my_collection_random', methods: ['GET'])]
    public function random(MyCollectionRepository $myCollectionRepository)
    {
        // retrieve a random collection
        $collection = $myCollectionRepository->getRandomMovie();
        return $this->json(
            $collection,
            200,
            [],
            ['groups' => ['get_collections']]
        );
    }
    */
    
}