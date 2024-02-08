<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\MyObject;
use App\Repository\CommentRepository;
use App\Repository\MyObjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// all comment avialable on MyCollectionController
#[Route('/api')]
class CommentController extends AbstractController
{
    /**
     * list all comments
     *
     * @param CommentRepository $commentRepository
     * @return Response
     */
    #[Route('/comments', name: 'api_comment_list')]
    public function list(CommentRepository $commentRepository): Response
    {   
        $comments = $commentRepository->findAll();

        if (!$comments) { 
            return $this->json(
                "Error : Commentaires inexistants",
                404
            );
        }

        return $this->json(
            $comments,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_comments']
        );
    }

    /**
     * list one comment by its id
     *
     * @param CommentRepository $myCollectionRepository
     * @return Response
     */
    #[Route('/comment/{id}', name: 'api_my_comment_show',methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        if (!$comment) {
            return $this->json(
                "Error : Commentaire inexistant",
                404
            );
        }

        return $this->json(
            $comment,
            200,
            ['Access-Control-Allow-Origin' => '*'],
            ['groups' => 'get_comments']
            );
    } 

    /**
    * create one comment 
    *
    * @param MyCommentRepository $myCollectionRepository
    * @return Response
    */
    #[Route('/comment/create', name: 'api_my_comment_create',methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, UserRepository $userRepository, MyObjectRepository $myObjectRepository)
    {
        // retrieve user
        $user = $userRepository->find(3);
        // retrieve object
        $object = $myObjectRepository->find(10);

        // set datas
        $comment = new Comment();
        $comment->setContent("Content");
        $comment->setUser($user);
        $comment->setMyObject($object);
 
        // record in database
        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->json([201, ['message' => 'create successful']]);

    }

    /**
    * update one collection
    *
    * @param CommentRepository $CommentRepository
    * @return Response
    */
    
    //#[Route('/collection/update/{id}', name: 'api_my_collection_update',methods: ['PUT'])]
    //public function update(Comment $comment = null, EntityManagerInterface $entityManager): Response
    //{
        // check if $myCollection doesn't exist
      //  if (!$comment) {
        //    return $this->json(
         //       "Error : Commentaire inexistante",
                // status code
                // 404
    //         );
    //     }
        
    //     $myCollection->setName("hrthsrths");
    //     $myCollection->setImage("hsrthsrth");
    //     $myCollection->setDescription("thsrthsrthszrth");
    //     $myCollection->setRating(5);
    //     $entityManager->flush();
        
    //     return $this->json(['message' => 'updated successful', 200]);
        
    // }
}
