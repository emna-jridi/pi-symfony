<?php

namespace App\Controller;

use App\Entity\Reunion;
use App\Form\ReunionType;
use App\Repository\ReunionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reunion')]
final class ReunionController extends AbstractController
{
    #[Route(name: 'app_reunion_index', methods: ['GET'])]
    public function index(ReunionRepository $reunionRepository): Response
    {
        return $this->render('reunion/index.html.twig', [
            'reunions' => $reunionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reunion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reunion = new Reunion();
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reunion);
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reunion/new.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_show', methods: ['GET'])]
    public function show(Reunion $reunion): Response
    {
        return $this->render('reunion/show.html.twig', [
            'reunion' => $reunion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reunion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reunion/edit.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_delete', methods: ['POST'])]
    public function delete(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reunion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reunion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
    }
}
