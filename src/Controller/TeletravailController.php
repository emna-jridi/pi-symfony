<?php

namespace App\Controller;

use App\Entity\Teletravail;
use App\Form\TeletravailType;
use App\Repository\TeletravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teletravail')]
final class TeletravailController extends AbstractController
{
    #[Route(name: 'app_teletravail_index', methods: ['GET'])]
    public function index(TeletravailRepository $teletravailRepository): Response
    {
        return $this->render('teletravail/index.html.twig', [
            'teletravails' => $teletravailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_teletravail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teletravail = new Teletravail();
    
        // Définir la date actuelle pour DateDemandeTT
        $teletravail->setDateDemandeTT(new \DateTime());
    
        // Définir la valeur par défaut pour StatutTT
        $teletravail->setStatutTT('Traitement');
    
        $form = $this->createForm(TeletravailType::class, $teletravail);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teletravail);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_teletravail_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('teletravail/new.html.twig', [
            'teletravail' => $teletravail,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{IdTeletravail}', name: 'app_teletravail_show', methods: ['GET'])]
    public function show(Teletravail $teletravail): Response
    {
        return $this->render('teletravail/show.html.twig', [
            'teletravail' => $teletravail,
        ]);
    }


    #[Route('/{IdTeletravail}/edit', name: 'app_teletravail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teletravail $teletravail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeletravailType::class, $teletravail);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_teletravail_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('teletravail/edit.html.twig', [
            'teletravail' => $teletravail,
            'form' => $form,
        ]);
    }
    #[Route('/{IdTeletravail}', name: 'app_teletravail_delete', methods: ['POST'])]
    public function delete(Request $request, Teletravail $teletravail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teletravail->getIdTeletravail(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($teletravail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teletravail_index', [], Response::HTTP_SEE_OTHER);
    }
}
