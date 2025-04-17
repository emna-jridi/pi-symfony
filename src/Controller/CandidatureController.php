<?php

namespace App\Controller;

use App\Enum\Statut;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CandidatureController extends AbstractController
{
    #[Route('/candidature', name: 'app_candidature')]
    public function index(): Response
    {
        return $this->render('candidature/index.html.twig', [
            'controller_name' => 'CandidatureController',
        ]);
    }



    #[Route('/listCandidatures', name: 'list_candidatures')]
    public function listCandidature(EntityManagerInterface $entityManager): Response
    {
        $candidatures = $entityManager->getRepository(Candidature::class)->findAll();

        return $this->render('candidature/listCandidatures.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }



    #[Route('/addCandidature', name: 'app_candidature_new')]
public function newCandidature(Request $request, EntityManagerInterface $entityManager): Response
{
    $candidature = new Candidature();
    $form = $this->createForm(CandidatureType::class, $candidature);

    if ($request->isMethod('POST')) {
        dump("Le formulaire a été soumis !");
    }

    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if (!$form->isValid()) {
            
            dump("Le formulaire n'est pas valide !");
            foreach ($form->getErrors(true) as $error) {
                dump($error->getMessage());
            }
        } else {
            $candidature->setStatut(Statut::EN_COURS);
            dump("Le formulaire est valide, on persiste !");
            $entityManager->persist($candidature);
            $entityManager->flush();

            $this->addFlash('success', 'Candidature ajoutée avec succès !');
            return $this->redirectToRoute('list_candidatures'); // Change vers la bonne route de liste
        }
    }

    return $this->render('candidature/addCandidature.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/{id}/editcandidature', name: 'app_candidature_edit', methods: ['GET', 'POST'])]
public function editCandidature(Request $request, Candidature $candidature, EntityManagerInterface $entityManager, int $id): Response
{  $candidature = $entityManager->getRepository(Candidature::class)->find($id);
    
    if (!$candidature) {
        $this->addFlash('error', 'Candidature non trouvée.');
        return $this->redirectToRoute('list_candidatures');
    }
    
    $form = $this->createForm(CandidatureType::class, $candidature);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été modifiée avec succès.');

        return $this->redirectToRoute('list_candidatures', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('candidature/editcandidature.html.twig', [
        'candidature' => $candidature,
        'form' => $form->createView(),
    ]);
}#[Route('/candidature/{id}/delete', name: 'app_candidature_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
public function deleteCandidature(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->request->get('_token'))) {
        $entityManager->remove($candidature);
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été supprimée avec succès.');
    }

    return $this->redirectToRoute('list_candidatures', [], Response::HTTP_SEE_OTHER);
}


#[Route('/listCandidaturesrh', name: 'list_candidaturesrh', methods: ['GET'])]
public function listcandidaturerh(EntityManagerInterface $entityManager): Response
{
    $candidatures = $entityManager->getRepository(Candidature::class)
        ->findBy(['statut' => Statut::EN_COURS]);

    return $this->render('candidature/listCandidaturesrh.html.twig', [
        'candidatures' => $candidatures,
    ]);
}




}
