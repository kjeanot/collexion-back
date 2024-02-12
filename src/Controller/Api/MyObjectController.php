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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(MyObjectRepository $myObjectRepository): Response
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
   #[Route('/object/create', name: 'api_my_object_create',methods: ['POST'])]
   public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, MyCollectionRepository $myCollectionRepository)
   {

    $category = $categoryRepository->find(20);
    $collection = $myCollectionRepository->find(13);

    $object = new MyObject();
    $object->setCategory($category);
    $object->setName('Object test api ');
    $object->setTitle('Test api ');
    $object->setImage('https://via.placeholder.com/150');
    $object->setDescription('Description test api ');
    $object->setState('State test api ');
    $collection->addMyobject($object);

    $entityManager->persist($object);
    $entityManager->persist($collection);
    $entityManager->flush();

    return $this->json([201, ['message' => 'create successful']]);

   }

    /**
    * update one object
    *
    * @param MyObjectRepository $myObjectRepository
    * @return Response
    */
    #[Route('/object/update/{id}', name: 'api_my_object_update',methods: ['PUT'])]
    public function update(MyObject $object = null, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        if (!$object) {
            return $this->json(
                "Error : Collection inexistante",
                404
            );
        }

        $category = $categoryRepository->find(15);
 
        $object->setCategory($category);
        $object->setName('Object je re test api ');
        $object->setTitle('Test je re api ');
        $object->setImage('https://via.placeholder.com/150');
        $object->setDescription('Description je re test api ');
        $object->setState('State je re test api ');
        $entityManager->flush();
 
        return $this->json(['message' => 'updated successful', 200]);
 
    }
    /**
    * delete one object
    * 
    * @param MyObjectRepository $myObjectRepository
    * @return Response
    */
    #[Route('/object/delete/{id}', name: 'api_my_collection_delete', methods: ['DELETE'])]
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
}