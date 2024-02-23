<?php

namespace App\Controller\Back;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\MyCollectionRepository;
use App\Repository\MyObjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/back/main', name: 'app_back_main')]
    public function index(UserRepository $userRepository, MyCollectionRepository $myCollectionRepository, MyObjectRepository $myObjectRepository, CommentRepository $commentRepository , CategoryRepository $categoryRepository): Response
    {
        $usersLimit = $userRepository->findAllLimit5();
        $myCollectionsLimit = $myCollectionRepository->findAllLimit5();
        $myObjectsLimit = $myObjectRepository->findAllLimit5();
        $commentsLimit = $commentRepository->findAllLimit5();
        $categoriesLimit = $categoryRepository->findAllLimit5();

        return $this->render('back/main/index.html.twig', [
            'myCollectionsLimit' => $myCollectionsLimit,
            'myObjectsLimit'=> $myObjectsLimit,
            'commentsLimit' => $commentsLimit,
            'categoriesLimit' => $categoriesLimit,
            'usersLimit' => $usersLimit,
        ]);
    }
}
