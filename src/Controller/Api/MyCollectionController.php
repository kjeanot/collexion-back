<?php

namespace App\Controller\Api;

use App\Entity\MyCollection;
use App\Entity\MyObject;
use App\Entity\User;
use App\Repository\MyObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MyCollectionRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            ['groups' => 'get_collection']
            );
    } 
    
    /**
    * create one collection 
    *
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
   #[Route('/secure/collection', name: 'api_my_collection_create',methods: ['POST'])]
   public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
   {

    $updateMyCollection = $serializer->deserialize($request->getContent(), MyCollection::class, 'json');

    $myCollection = new MyCollection();

    $myCollection->setUser($this->getUser());
    $myCollection->setName($updateMyCollection->getName());
    $myCollection->setDescription($updateMyCollection->getDescription());
    $myCollection->setImage($updateMyCollection->getImage());
    $myCollection->setUpdatedAt(New DateTimeImmutable());
    $myCollection->setIsActive($updateMyCollection->isIsActive());

    $violations = $validator->validate($myCollection);

    if (0 !== count($violations)) {
        return $this->json([$violations,500,['message' => 'error']]); ;
    } else{
        
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
    #[Route('/secure/collection/{id}', name: 'api_my_collection_update',methods: ['PUT'])]

    public function update(MyCollection $myCollection = null, EntityManagerInterface $entityManager , SerializerInterface $serializer, Request $request,MyObjectRepository $myObjectRepository, ValidatorInterface $validator): Response
    {
        
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }
        // retrieve data from request
        $jsonData = json_decode($request->getContent(), true);
        // deserialize data
        $updateMyCollection = $serializer->deserialize($request->getContent(), MyCollection::class, 'json');
        // retrieve related objects
        $myObjectId = $jsonData['relatedObjects'];

        $myCollection->setUser($this->getUser());
        $myCollection->setName($updateMyCollection->getName());
        $myCollection->setDescription($updateMyCollection->getDescription());
        $myCollection->setImage($updateMyCollection->getImage());
        $myCollection->setUpdatedAt(New DateTimeImmutable());
        $myCollection->setIsActive($updateMyCollection->isIsActive());
        foreach ($myObjectId as $object) {
            $objectId = $object['id'];
            $objectToRemove = $myObjectRepository->find($objectId);
            if ($objectToRemove) {
                $myCollection->removeMyobject($objectToRemove);
            }
        }

        $violations = $validator->validate($myCollection);
        // if there is an error
        if (0 !== count($violations)) {
            return $this->json([$violations,500,['message' => 'error']]); ;
        } else {    
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
    #[Route('/secure/collection/{id}', name: 'api_my_collection_delete', methods: ['DELETE'])]
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

    /**
     * @Route("/uploadFile", name="upload", methods={"POST"})
     */
    #[Route('/secure/collection/upload_file', name: 'api_collection_upload_file', methods: ['POST'])]
    public function upload(Request $request, MyCollectionRepository $myCollectionRepository, ParameterBagInterface $params, MyCollection $myCollection,EntityManagerInterface $manager)
    {
        // for test only in the back side
         $myCollection = $myCollectionRepository->find(2);

        $image = $request->files->get('file');

        $validator = Validation::createValidator();
        $violations = $validator->validate($image);

        if (0 !== count($violations)) {
            return $this->json([$violations,500,['message' => 'error']]); ;
        } else{
           
            // on ajoute uniqid() afin de ne pas avoir 2 fichiers avec le même nom
            $newFilename = uniqid().'.'. $image->getClientOriginalName();

             // enregistrement de l'image dans le dossier public du serveur
            // paramas->get('public') =>  va chercher dans services.yaml la variable public
            $image->move($params->get('images_collections'), $newFilename);

            // ne pas oublier d'ajouter l'url de l'image dans l'entitée aproprié
            // $entity est l'entity qui doit recevoir votre image
            $myCollection->setImage($newFilename);

            $manager->flush();

            return $this->json([
                'message' => 'Image uploaded successfully.'
            ]);
        }
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
            [],
            // groups authorized
            ['groups' => 'get_collection_random']
        );
    }
    #[Route('/secure/add/{id}/favorite', name: 'api_add_collection_favorite',methods: ['POST'])]
    public function newFavorite(MyCollection $myCollection = null, EntityManagerInterface $entityManager,): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }
        // add user to collection favorite
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
            [],
            // groups authorized
            ['groups' => 'get_favorite'],
            ['message' => 'add successful']
        );
    }

        #[Route('/secure/delete/{id}/favorite', name: 'api_delete_collection_favorite',methods: ['POST'])]
    public function deleteFavorite(MyCollection $myCollection = null, EntityManagerInterface $entityManager): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }
        // remove user from collection favorite
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
