<?php

namespace App\Controller\Api;

use App\Entity\MyCollection;
use App\Entity\User;
use App\Repository\MyCollectionRepository;
use App\Repository\MyObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;

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
            ['Access-Control-Allow-Origin' => '*' ],
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
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_collection']
            );
    } 
    
    /**
    * create one collection 
    *
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
   #[Route('/collection', name: 'api_my_collection_create',methods: ['POST'])]
   public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, MyObjectRepository $myObjectRepository)
   {

    // $jsonData = json_decode($request->getContent(), true);

    $myCollection = $serializer->deserialize($request->getContent(), MyCollection::class, 'json');
    // dd($myCollection);

    // $myObjectId = $jsonData['myobjects'];

    $validator = Validation::createValidator();
    $violations = $validator->validate($myCollection);

    if (0 !== count($violations)) {
        return $this->json([$violations,500,['message' => 'error']]); ;
    } else{
        // retrieve user
        $myCollection->setUser($this->getUser());
        // foreach ($myObjectId as $object) {
        //     $objectId = $object['id'];
        //     $objectToAdd = $myObjectRepository->find($objectId);
        //     if ($objectToAdd) {
        //         $myCollection->addMyobject($objectToAdd);
        //     }
        // }
        
        $entityManager->persist($myCollection);
        $entityManager->flush();

        return $this->json($serializer->serialize($myCollection, 'json', ['groups' => 'collection']), 201, ['message' => 'create successful']);
   }
}

    /**
    * update one collection
    *
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
    #[Route('/collection/{id}', name: 'api_my_collection_update',methods: ['PUT'])]
    public function update(MyCollection $myCollection = null, EntityManagerInterface $entityManager , SerializerInterface $serializer , Request $request, MyObjectRepository $myObjectRepository): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }

        // $jsonData = json_decode($request->getContent(), true);

        $updateMyCollection = $serializer->deserialize($request->getContent(), MyCollection::class, 'json');

        // $myObjectId = $jsonData['myobjects'];
        
        $validator = Validation::createValidator();
        $violations = $validator->validate($updateMyCollection);

        if (0 !== count($violations)) {
            return $this->json([$violations,500,['message' => 'error']]); ;
        } else{
            $myCollection->setUser($this->getUser());
            $myCollection->setName($updateMyCollection->getName());
            $myCollection->setDescription($updateMyCollection->getDescription());
            $myCollection->setImage($updateMyCollection->getImage());
            // foreach ($myObjectId as $object) {
            //     $objectId = $object['id'];
            //     $objectToRemove = $myObjectRepository->find($objectId);
            //     if ($objectToRemove) {
            //         $myCollection->removeMyobject($objectToRemove);
            //     }
            // }
            
            $entityManager->flush();

            return $this->json($serializer->serialize($myCollection, 'json', ['groups' => 'collection']), 200, ['message' => 'update successful']);
        }
 
    }

    /**
    * delete one collection
    * 
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
    #[Route('/collection/{id}', name: 'api_my_collection_delete', methods: ['DELETE'])]
    public function delete(MyCollection $myCollection, EntityManagerInterface $manager): Response
    {
         // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                ['message' => 'collection inexistant'],
                404,
                );
        }

        $manager->remove($myCollection);
        $manager->flush();

        return $this->json(['message' => 'delete successful', 200]);
       
    }
    #[Route('/collection_random', name: 'api_my_collection_random',methods: ['GET'])]
    public function random(MyCollectionRepository $myCollectionRepository): Response
    {
        // retrieve all collections
        $collections = $myCollectionRepository->findRandomCollectionSql();
        
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
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_collections']
        );
    }
    #[Route('/add/{id}/favorite', name: 'api_add_collection_favorite',methods: ['POST'])]
    public function newFavorite(MyCollection $myCollection, EntityManagerInterface $entityManager,): Response
    {
        
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }

        $myCollection->addUser($this->getUser());


        $entityManager->persist($myCollection);
        $entityManager->flush();

        // return json
        return $this->json(
            // what I want to show
            $myCollection,
            // status code
            200,
            // header
            ['Access-Control-Allow-Origin' => '*' ],
            // groups authorized
            ['groups' => 'get_favorite'],
            ['message' => 'add successful']
        );
    }

        #[Route('/delete/{id}/favorite', name: 'api_delete_collection_favorite',methods: ['POST'])]
    public function deleteFavorite(MyCollection $myCollection, EntityManagerInterface $entityManager): Response
    {


        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }

        $myCollection->removeUser($this->getUser());


        $entityManager->persist($myCollection);
        $entityManager->flush();

        // return json
        return $this->json(
            // what I want to show
            $myCollection,
            // status code
            200,
            // header
            [],
            // groups authorized
            ['groups' => 'get_favorite'],
            ['message' => 'delete successful']
        );
    }
}