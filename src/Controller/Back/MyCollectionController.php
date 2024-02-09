<?php

namespace App\Controller\Back;

use App\Entity\MyCollection;
use App\Form\MyCollectionType;
use App\Repository\MyCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/my/collection')]
class MyCollectionController extends AbstractController
{
    #[Route('/', name: 'app_back_my_collection_index', methods: ['GET'])]
    public function index(MyCollectionRepository $myCollectionRepository): Response
    {
        return $this->render('back/my_collection/index.html.twig', [
            'my_collections' => $myCollectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_my_collection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $myCollection = new MyCollection();
        $form = $this->createForm(MyCollectionType::class, $myCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($myCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_my_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/my_collection/new.html.twig', [
            'my_collection' => $myCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_my_collection_show', methods: ['GET'])]
    public function show(MyCollection $myCollection): Response
    {
        return $this->render('back/my_collection/show.html.twig', [
            'my_collection' => $myCollection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_my_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MyCollection $myCollection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MyCollectionType::class, $myCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_my_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/my_collection/edit.html.twig', [
            'my_collection' => $myCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_my_collection_delete', methods: ['POST'])]
    public function delete(Request $request, MyCollection $myCollection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$myCollection->getId(), $request->request->get('_token'))) {
            $entityManager->remove($myCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_my_collection_index', [], Response::HTTP_SEE_OTHER);
    }
}
