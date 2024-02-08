<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Repository\CommentRepository;
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
}
