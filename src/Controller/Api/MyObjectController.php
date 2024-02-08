<?php

namespace App\Controller\Api;

use App\Entity\MyObject;
use App\Repository\MyObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}