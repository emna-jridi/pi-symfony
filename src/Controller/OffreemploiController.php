<?php

namespace App\Controller;

use App\Entity\Offreemploi;
use App\Form\FiltreOffreType;
use App\Form\OffreemploiType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreemploiRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function newOffre(Request $request, EntityManagerInterface $entityManager): Response
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
                $offreemploi->setDateCreation(new \DateTime());
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
public function listOffre(Request $request,EntityManagerInterface $entityManager): Response
{
    
    // Récupérer toutes les offres depuis la base de données
    $offres = $entityManager->getRepository(Offreemploi::class)->findAll();

    // Affichage de la liste des offres
    return $this->render('offreemploi/listOffres.html.twig', [
        'offres' => $offres,
    ]);
}


#[Route('/{id}/edit', name: 'app_offreemploi_edit', methods: ['GET', 'POST'])]
public function editOffre(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
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

#[Route('/{id}', name: 'app_offreemploi_delete',requirements: ['id' => '\d+'] ,methods: ['POST'])]
public function deleteOffre(Request $request, Offreemploi $offreemploi, EntityManagerInterface $entityManager): Response
{
        $entityManager->remove($offreemploi);
        $entityManager->flush();
        
        $this->addFlash('success', 'L\'offre a été supprimée avec succès.');
    

    return $this->redirectToRoute('list_offres', [], Response::HTTP_SEE_OTHER);
}


/* 
// Afficher la liste des offres
#[Route('/listOffresCandidat', name: 'list_offrescandidat')]
public function listOffresCandidat(EntityManagerInterface $entityManager): Response
{
    
    // Récupérer toutes les offres depuis la base de données
    $offres = $entityManager->getRepository(Offreemploi::class)->findAll();

    // Affichage de la liste des offres
    return $this->render('offreemploi/listOffresCandidat.html.twig', [
        'offres' => $offres,
    ]);
}
 */
// Afficher la liste des offres
#[Route('/listOffresCandidat', name: 'list_offrescandidat')]
public function listOffresCandidat(Request $request, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(FiltreOffreType::class);
    $form->handleRequest($request);
    
    // Initialiser le repository
    $offreRepository = $entityManager->getRepository(Offreemploi::class);
    
    // Valeurs par défaut (pas de filtrage)
    $typeContrat = null;
    $niveauEtudes = null;
    
    // Si le formulaire est soumis et valide, récupérer les critères de filtrage
    if ($form->isSubmitted() && $form->isValid()) {
        $filtres = $form->getData();
        $typeContrat = $filtres['typeContrat'] ?? null;
        $niveauEtudes = $filtres['niveauEtudes'] ?? null;
        
        // Utiliser la méthode de filtrage
        $offres = $offreRepository->findByFiltres($typeContrat, $niveauEtudes);
    } else {
        // Si pas de filtrage, récupérer toutes les offres
        $offres = $offreRepository->findAll();
    }

    // NE PAS remplacer les offres filtrées avec toutes les offres
    // Cette ligne est à supprimer :
    // $offres = $entityManager->getRepository(Offreemploi::class)->findAll();

    // Affichage de la liste des offres avec le formulaire
    return $this->render('offreemploi/listOffresCandidat.html.twig', [
        'offres' => $offres,
        'filtreForm' => $form->createView(),  // Transmettre le formulaire au template
    ]);
}
#[Route('/offres/search', name: 'app_offre_search')]
    public function search(Request $request, OffreemploiRepository $offreRepository): Response
    {
        $searchTerm = $request->query->get('q', '');
        
        // Vérifie que c'est une requête AJAX
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('app_offre_index');
        }
        
        // Recherche dans le repository
        $offres = $offreRepository->searchByTerm($searchTerm);
        
        // Retourne uniquement le fragment HTML de la liste
        return $this->render('offreemploi/_listOffresCandidat.html.twig', [
            'offres' => $offres,
        ]);
    }
    #[Route('/offre/{id}', name: 'app_offre_show')]
    public function show(Offreemploi $offreemploi): Response
    {
        // La résolution automatique par ParamConverter trouve l'offre par son ID
        
        return $this->render('offreemploi/detailsOffresCandidat.html.twig', [
            'offre' => $offreemploi,
            'isExpired' => $offreemploi->getDateExpiration() < new \DateTime()
        ]);
    }
  

}