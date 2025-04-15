<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Form\CongeTypeadmin;
use App\Repository\CongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conge')]
final class CongeController extends AbstractController
{
    #[Route(name: 'app_conge_index', methods: ['GET'])]
    public function index(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'app_conge_search', methods: ['GET'])]
    public function search(Request $request, CongeRepository $congeRepository): Response
    {
        $typeConge = $request->query->get('type');
        
        if ($typeConge) {
            $conges = $congeRepository->findBy(['Type_conge' => $typeConge]);
        } else {
            $conges = $congeRepository->findAll();
        }

        return $this->render('conge/_conges_list.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conge = new Conge();
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conge->setStatus('En attente');
            $entityManager->persist($conge);
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_show', methods: ['GET'])]
    public function show(Conge $conge): Response
    {
        return $this->render('conge/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_delete', methods: ['POST'])]
    public function delete(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit/admin', name: 'app_conge_edit_admin', methods: ['GET', 'POST'])]
    public function edit_admin(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongeTypeadmin::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit_admin.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route("/conge_approve/admin",name: 'app_conge_index_admin', methods: ['GET'])]
    public function indexadmin(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index_admin.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }
}
