<?php

namespace App\Controller;

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
    public function list(EntityManagerInterface $entityManager): Response
    {
        $candidatures = $entityManager->getRepository(Candidature::class)->findAll();

        return $this->render('candidature/listCandidatures.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }



    #[Route('/addCandidature', name: 'app_candidature_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
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

}
