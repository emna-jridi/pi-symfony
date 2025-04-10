<?php

namespace App\Controller;

use App\Entity\Offreemploi;
use App\Form\OffreemploiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OffreemploiController extends AbstractController
{
    #[Route('/offreemploi', name: 'app_offreemploi')]
    public function index(): Response
    {
        return $this->render('offreemploi/index.html.twig', [
            'controller_name' => 'OffreemploiController',
        ]);
    }

    #[Route('/addOffre', name: 'app_offreemploi_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $offreemploi = new Offreemploi();
    $form = $this->createForm(OffreemploiType::class, $offreemploi);
    
    // Affiche un message si le formulaire est soumis ou non
    if ($request->isMethod('POST')) {
        dump("Le formulaire a été soumis !");
    }

    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if (!$form->isValid()) {
            // Afficher les erreurs du formulaire pour debug
            dump("Le formulaire n'est pas valide !");
            foreach ($form->getErrors(true) as $error) {
                dump($error->getMessage());
            }
        } else {
            // Si le formulaire est valide, persist et flush
            dump("Le formulaire est valide, on persiste !");
            $entityManager->persist($offreemploi);
            $entityManager->flush();

            $this->addFlash('success', 'Offre ajoutée avec succès !');
            return $this->redirectToRoute('list_offres');
        }
    }

    return $this->render('offreemploi/addOffre.html.twig', [
        'form' => $form->createView(),
    ]);
}


// Afficher la liste des offres
#[Route('/listOffres', name: 'list_offres')]
public function list(EntityManagerInterface $entityManager): Response
{
    // Récupérer toutes les offres depuis la base de données
    $offres = $entityManager->getRepository(Offreemploi::class)->findAll();

    // Affichage de la liste des offres
    return $this->render('offreemploi/listOffres.html.twig', [
        'offres' => $offres,
    ]);
}


#[Route('/{id}/edit', name: 'app_offreemploi_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(OffreemploiType::class, $offreemploi);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'L\'offre a été modifiée avec succès.');

        return $this->redirectToRoute('list_offres', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('offreemploi/edit.html.twig', [
        'offreemploi' => $offreemploi,
        'form' => $form->createView(),
    ]);
}

#[Route('/{id}', name: 'app_offreemploi_delete', methods: ['POST'])]
public function delete(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
{
        $entityManager->remove($offreemploi);
        $entityManager->flush();
        
        $this->addFlash('success', 'L\'offre a été supprimée avec succès.');
    

    return $this->redirectToRoute('list_offres', [], Response::HTTP_SEE_OTHER);
}

}