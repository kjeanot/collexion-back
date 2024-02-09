<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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

           /**
     * list one category by its id
     *
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/category/{id}', name: 'api_my_category_show',methods: ['GET'])]
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

    /**
    * create one categoryParent
    *
    * @param CategoryRepository $categoryRepository
    * @return Response
    */
    #[Route('/category/parent/create', name: 'api_my_category_create',methods: ['POST'])]
    public function createParent(EntityManagerInterface $entityManager)
    {

        $parentCategory = new Category();
        $parentCategory->setName('Category parent ceci est un test api 1 ');
        $parentCategory->setImage('https://via.placeholder.com/150');
 
        $entityManager->persist($parentCategory);
        $entityManager->flush();
        return $this->json([201, ['message' => 'create successful']]);

    }

        /**
    * create one categoryChild
    *
    * @param CategoryRepository $categoryRepository
    * @return Response
    */
    #[Route('/category/child/create', name: 'api_my_category_create',methods: ['POST'])]
    public function createEnfant(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {

        $parentCategory = $categoryRepository->find(41);

        $enfantCategory = new Category();
        $enfantCategory->setName('Category enfant ceci est un test api qui sera lier a un autre test api parent');
        $enfantCategory->setImage('https://via.placeholder.com/150');
        $entityManager->persist($enfantCategory);

        $parentCategory->addCategory($enfantCategory);
 
        $entityManager->persist($parentCategory);
        $entityManager->flush();
        return $this->json([201, ['message' => 'create successful']]);

    }
        /**
    * update one category
    *
    * @param CategoryRepository $categoryRepository
    * @return Response
    */
    #[Route('/category/update/{id}', name: 'api_my_category_update',methods: ['PUT'])]
    public function update(Category $category = null, EntityManagerInterface $entityManager): Response
    {
        if (!$category) {
            return $this->json(
                "Error : Categorie inexistante",
                404
            );
        }
        
        $category->setName('Category enfant Je retest api update sur une category parent');
        $category->setImage('https://via.placeholder.com/150');

        $entityManager->flush();
 
        return $this->json(['message' => 'updated successful', 200]);
 
    }

        /**
    * delete one category
    * 
    * @param CategoryRepository $categoryRepository
    * @return Response
    */
    #[Route('/category/delete/{id}', name: 'api_my_category_delete', methods: ['DELETE'])]
    public function delete(Category $category = null , EntityManagerInterface $entityManager): Response
    {
         // check if $category doesn't exist
        if (!$category) {
            return $this->json(
                ['message' => 'collection inexistant'],
                404,
                );
        }
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(['message' => 'delete successful', 200]);
       
    }
}
