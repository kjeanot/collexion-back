<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CategoryController extends AbstractController
{
        /**
     * list all Categories
     *
     * @param CategoryRepository $CategoryRepository
     * @return Response
     */
    #[Route('/categories', name: 'api_category_list')]
    public function list(CategoryRepository $categoryRepository): Response
    {   
        $categories = $categoryRepository->findAll();

        if (!$categories) { 
            return $this->json(
                "Error : Categories inexistantes",
                404
            );
        }

        return $this->json(
            $categories,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_categories']
        );
    }
}
