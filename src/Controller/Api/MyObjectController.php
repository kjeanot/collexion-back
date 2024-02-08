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
     * @param MyObjectsRepository $myObjectsRepository
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
            [],
            ['groups' => 'get_objects']
        );
    }
}