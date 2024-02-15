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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\SecurityBundle\Security;

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
     * @param CommentRepository $commentRepository
     * @return Response
     */
    #[Route('/comment/{id}', name: 'api_comment_show',methods: ['GET'])]
    public function show(Comment $comment = null): Response
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
    * @param CommentRepository $commentRepository
    * @return Response
    */
    #[Route('/comment', name: 'api_comment_create',methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, UserRepository $userRepository, MyObjectRepository $myObjectRepository, SerializerInterface $serializer, Request $request, Security $security)
    {
    $jsonData = json_decode($request->getContent(), true);
    $comment = $serializer->deserialize($request->getContent(), Comment::class, 'json');

    $myObjectId = $jsonData['object'];
    $myObject = $myObjectRepository->find($myObjectId);

    if (!$myObject) {
        return $this->json(['message' => 'Object not found'], 404);
    }

    $validator = Validation::createValidator();
    $violations = $validator->validate($comment);

    if (0 !== count($violations)) {
        return $this->json([$violations,500,['message' => 'error']]); ;
    } else{
        $comment->setUser($security->getUser());
        $comment->setMyObject($myObject);
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->json($serializer->serialize($comment, 'json', ['groups' => 'comment']), 201, ['message' => 'create successful']);
   }


    }

    /**
    * update one comment
    *
    * @param CommentRepository $commentRepository
    * @return Response
    */
    #[Route('/comment/{id}', name: 'api_comment_update',methods: ['PUT'])]
    public function update(Comment $comment = null, EntityManagerInterface $entityManager,MyObjectRepository $myObjectRepository, SerializerInterface $serializer, Request $request, Security $security): Response
    {
        // check if $comment doesn't exist
        if (!$comment) {
            return $this->json(
                "Error : Commentaire inexistant",
                // status code
                404
            );
        }

        $jsonData = json_decode($request->getContent(), true);

        $updateComment = $serializer->deserialize($request->getContent(), Comment::class, 'json');

        $myObjectId = $jsonData['object'];
        $updateMyObject = $myObjectRepository->find($myObjectId);

        if (!$updateMyObject) {
            return $this->json(['message' => 'Object not found'], 404);
        }

        $validator = Validation::createValidator();
        $violations = $validator->validate($updateComment);

        if (0 !== count($violations)) {
            return $this->json([$violations,500,['message' => 'error']]); ;
        } else{
            $comment->setUser($security->getUser());
            $comment->setMyObject($updateMyObject);
            $comment->setContent($updateComment->getContent());

            $entityManager->flush();

            return $this->json($serializer->serialize($comment, 'json', ['groups' => 'comment']), 200, ['message' => 'update successful']);
        }
 
    }

    /**
    * delete one comment
    * 
    * @param CommentRepository $commentRepository
    * @return Response
    */
    #[Route('/comment/{id}', name: 'api_comment_delete', methods: ['DELETE'])]
    public function delete(Comment $comment = null , EntityManagerInterface $entityManager): Response
    {
         // check if $comment doesn't exist
        if (!$comment) {
            return $this->json(
                ['message' => 'collection inexistant'],
                404,
                );
        }
        // delete 
        $entityManager->remove($comment);
        // record in database
        $entityManager->flush();

        return $this->json(['message' => 'delete successful', 200]);
       
    }
}
