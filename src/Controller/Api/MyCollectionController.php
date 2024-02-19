<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\User;
use App\Entity\MyCollection;
use App\Form\MyCollectionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MyCollectionRepository;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
            ['groups' => 'get_collections']
            );
    } 
    
    /**
    * create one collection 
    *
    * @param MyCollectionRepository $myCollectionRepository
    * @return Response
    */
   #[Route('/collection', name: 'api_my_collection_create',methods: ['POST'])]
   public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer)
   {

    $myCollection = $serializer->deserialize($request->getContent(), MyCollection::class, 'json');

    $validator = Validation::createValidator();
    $violations = $validator->validate($myCollection);

    if (0 !== count($violations)) {
        return $this->json([$violations,500,['message' => 'error']]); ;
    } else{
        $myCollection->setUser($this->getUser());
        
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
    public function update(MyCollection $myCollection = null, EntityManagerInterface $entityManager , SerializerInterface $serializer , Request $request): Response
    {
        // check if $myCollection doesn't exist
        if (!$myCollection) {
            return $this->json(
                "Error : Collection inexistante",
                // status code
                404
            );
        }

        $updateMyCollection = $serializer->deserialize($request->getContent(), MyCollection::class, 'json');

        $validator = Validation::createValidator();
        $violations = $validator->validate($updateMyCollection);

        if (0 !== count($violations)) {
            return $this->json([$violations,500,['message' => 'error']]); ;
        } else{
            $myCollection->setUser($this->getUser());
            $myCollection->setName($updateMyCollection->getName());
            $myCollection->setDescription($updateMyCollection->getDescription());
            $myCollection->setImage($updateMyCollection->getImage());
            $myCollection->setRating($updateMyCollection->getRating());

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

    /**
     * @Route("/uploadFile", name="upload", methods={"POST"})
     */
    #[Route('/upload_file', name: 'api_upload_file', methods: ['POST'])]
    public function upload(Request $request, ParameterBagInterface $params, MyCollection $myCollection)
    {
        dd($request);

        $image = $request->files->get('file');

        // check if $image exist
        /* if ($image === null) {
            throw new Exception("L'objet image est null");
        } */
        
        // enregistrement de l'image dans le dossier public du serveur
        // paramas->get('public') =>  va chercher dans services.yaml la variable public
        $image->move($params->get('public'), $image->getClientOriginalName());

				
        // on ajoute uniqid() afin de ne pas avoir 2 fichiers avec le même nom
        $newFilename = uniqid().'.'. $image->getClientOriginalName();
        // ne pas oublier d'ajouter l'url de l'image dans l'entitée aproprié
				// $entity est l'entity qui doit recevoir votre image
				$myCollection->setImage($newFilename);

        return $this->json([
            'message' => 'Image uploaded successfully.'
        ]);
    }
}