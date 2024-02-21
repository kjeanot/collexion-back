<?php

namespace App\Controller\Back;

use App\Entity\MyObject;
use App\Form\MyObjectType;
use App\Repository\MyObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/back/my/object')]
class MyObjectController extends AbstractController
{
    #[Route('/', name: 'app_back_my_object_index', methods: ['GET'])]
    public function index(MyObjectRepository $myObjectRepository): Response
    {
        return $this->render('back/my_object/index.html.twig', [
            'my_objects' => $myObjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_my_object_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $myObject = new MyObject();
        $form = $this->createForm(MyObjectType::class, $myObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageName = $form->get('image')->getData();
            
            if ($imageName) {
                $originalFilename = pathinfo($imageName->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = uniqid().'.'.$safeFilename.'.'.$imageName->guessExtension();
            
                // Move the file to the directory where brochures are stored
                try {
                    $imageName->move(
                        $this->getParameter('images_objects'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageNamename' property to store the PDF file name
                // instead of its contents
                $myObject->setimage($newFilename);
            }
            $entityManager->persist($myObject);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_my_object_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/my_object/new.html.twig', [
            'my_object' => $myObject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_my_object_show', methods: ['GET'])]
    public function show(MyObject $myObject): Response
    {
        return $this->render('back/my_object/show.html.twig', [
            'my_object' => $myObject,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_my_object_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MyObject $myObject, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MyObjectType::class, $myObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $imageName = $form->get('image')->getData();
            
            if ($imageName) {
                $originalFilename = pathinfo($imageName->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = uniqid().'.'.$safeFilename.'.'.$imageName->guessExtension();
            
                // Move the file to the directory where brochures are stored
                try {
                    $imageName->move(
                        $this->getParameter('images_objects'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageNamename' property to store the PDF file name
                // instead of its contents
                $myObject->setimage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_back_my_object_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/my_object/edit.html.twig', [
            'my_object' => $myObject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_my_object_delete', methods: ['POST'])]
    public function delete(Request $request, MyObject $myObject, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$myObject->getId(), $request->request->get('_token'))) {
            $entityManager->remove($myObject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_my_object_index', [], Response::HTTP_SEE_OTHER);
    }
}
