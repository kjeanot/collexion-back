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
    #[Route('/categories', name: 'api_categories_list')]
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

            /**
     * list all Categories
     *
     * @param CategoryRepository $CategoryRepository
     * @return Response
     */
    #[Route('/categories/parents', name: 'api_categories_parents')]
    public function listParents(CategoryRepository $categoryRepository): Response
    {   
        $categoriesParents = $categoryRepository->findAllCategoriesParent();

        if (!$categoriesParents) { 
            return $this->json(
                "Error : Categories inexistantes",
                404
            );
        }

        return $this->json(
            $categoriesParents,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_categories']
        );
    }

                /**
     * list all Categories
     *
     * @param CategoryRepository $CategoryRepository
     * @return Response
     */
    #[Route('/categories/childs', name: 'api_categories_childs')]
    public function listChilds(CategoryRepository $categoryRepository): Response
    {   
        $categoriesChilds = $categoryRepository->findAllCategoriesChild();

        if (!$categoriesChilds) { 
            return $this->json(
                "Error : Categories inexistantes",
                404
            );
        }

        return $this->json(
            $categoriesChilds,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_categories']
        );
    }

               /**
     * list one category by its id
     *
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/category/parents/childs/{id}', name: 'api_categories_parents_childs',methods: ['GET'])]
    public function showChildsById(Category $category, CategoryRepository $categoryRepository): Response
    {

        $categoriesChilds = $categoryRepository->findAllCategoriesChildById($category->getId());

        if (!$categoriesChilds) {
            return $this->json(
                "Error : Categorie parent inexistant",
                404
            );
        }

        return $this->json(
            $categoriesChilds,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_categories']
            );
    } 

           /**
     * list one category by its id
     *
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/category/{id}', name: 'api_category_show',methods: ['GET'])]
    public function show(Category $category): Response
    {
        if (!$category) {
            return $this->json(
                "Error : Commentaire inexistant",
                404
            );
        }

        return $this->json(
            $category,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_categories']
            );
    } 
}
