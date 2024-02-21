<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\MyCollection;
use App\Entity\MyObject;
use App\Repository\CategoryRepository;
use App\Repository\MyCollectionRepository;
use App\Repository\MyObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;

// all comment avialable on MyCollectionController
#[Route('/api')]
class MyObjectController extends AbstractController
{
    
    /**
     * list all objects
     *
     * @param MyObjectRepository $myObjectsRepository
     * @return Response
     */
    #[Route('/objects', name: 'api_my_object_list')]
    public function list(MyObjectRepository $myObjectRepository): Response
    {
        $objects = $myObjectRepository->findAll();
        
        if (! $objects) {
            return $this->json(
                "Error :    Objets inexistants",
                404
            );
        }

        return $this->json(
            $objects,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_objects']
        );
    }

     /**
     * list one object by its id
     *
     * @param MyObjectRepository $myObjectRepository
     * @return Response
     */
    #[Route('/object/{id}', name: 'api_my_object_show',methods: ['GET'])]
    public function show(MyObject $myObject): Response
    {
        if (!$myObject) {
            return $this->json(
                "Error : Objet inexistant",
                404
            );
        }
        return $this->json(
            $myObject,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_objects']
            );
    } 

    /**
    * create one object 
    *
    * @param MyObjectRepository $myObjectRepository
    * @return Response
    */
   #[Route('/object', name: 'api_my_object_create',methods: ['POST'])]
   public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, Category $category = null, CategoryRepository $categoryRepository)
   {

    $jsonData = json_decode($request->getContent(), true);
    
    $myObject = $serializer->deserialize($request->getContent(), MyObject::class, 'json');

    $categoryId = $jsonData['category'];
    $category = $categoryRepository->find($categoryId);

    if (!$category) {
        return $this->json(['message' => 'Category not found'], 404);
    }

    $validator = Validation::createValidator();
    $violations = $validator->validate($myObject);

    if (0 !== count($violations)) {
        return $this->json([$violations, 500, ['message' => 'error']]);
    } else {
        $myObject->setCategory($category);
        $entityManager->persist($myObject);
        $entityManager->flush();

        return $this->json($serializer->serialize($myObject, 'json', ['groups' => 'object']), 201, ['message' => 'create successful']);
    }
   }

    /**
    * update one object
    *
    * @param MyObjectRepository $myObjectRepository
    * @return Response
    */
    #[Route('/object/{id}', name: 'api_my_object_update',methods: ['PUT'])]
    public function update(MyObject $myObject = null, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, SerializerInterface $serializer , Request $request): Response
    {
        if (!$myObject) {
            return $this->json(
                "Error : Collection inexistante",
                404
            );
        }

        $jsonData = json_decode($request->getContent(), true);

        $updateMyObject = $serializer->deserialize($request->getContent(), MyObject::class, 'json');

        $categoryId = $jsonData['category'];
        $updateCategory = $categoryRepository->find($categoryId);

        if (!$updateCategory) {
            return $this->json(['message' => 'Category not found'], 404);
        }

        $validator = Validation::createValidator();
        $violations = $validator->validate($updateMyObject);

        if (0 !== count($violations)) {
            return $this->json([$violations,500,['message' => 'error']]); ;
        } else{
            $myObject->setCategory($updateCategory);
            $myObject->setName($updateMyObject->getName());
            $myObject->setTitle($updateMyObject->getTitle());
            $myObject->setDescription($updateMyObject->getDescription());
            $myObject->setImage($updateMyObject->getImage());
            $myObject->setState($updateMyObject->getState());

            $entityManager->flush();

            return $this->json($serializer->serialize($myObject, 'json', ['groups' => 'object']), 200, ['message' => 'update successful']);
        }
    }
    
    /**
    * delete one object
    * 
    * @param MyObjectRepository $myObjectRepository
    * @return Response
    */
    #[Route('/object/{id}', name: 'api_my_object_delete', methods: ['DELETE'])]
    public function delete(MyObject $Object, EntityManagerInterface $manager): Response
    {
        if (!$Object) {
            return $this->json(
                ['message' => 'objet inexistant'],
                404,
                );
        }

        $manager->remove($Object);
        $manager->flush();

        return $this->json(['message' => 'delete successful', 200]);
       
    }

    #[Route('/object/upload_file', name: 'api_object_upload_file', methods: ['POST'])]
    public function upload(Request $request, ParameterBagInterface $params, MyObject $myObject,EntityManagerInterface $manager)
    {
        // for test only in the back side
        //  $myObject = $myObjectRepository->find(4);

        $image = $request->files->get('file');
        
        // enregistrement de l'image dans le dossier public du serveur
        // paramas->get('public') =>  va chercher dans services.yaml la variable public
        $image->move($params->get('images_objects'), $image->getClientOriginalName());
				
        // on ajoute uniqid() afin de ne pas avoir 2 fichiers avec le même nom
        $newFilename = uniqid().'.'. $image->getClientOriginalName();

        // ne pas oublier d'ajouter l'url de l'image dans l'entitée aproprié
		// $entity est l'entity qui doit recevoir votre image
		$myObject->setImage($newFilename);

        $manager->flush();

        return $this->json([
            'message' => 'Image uploaded successfully.'
        ]);
    }
}