<?php

namespace App\Controller;

use App\Enum\Statut;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Form\FiltreCandidatureType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreemploiRepository;
use App\Services\ServiceMailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class CandidatureController extends AbstractController
{



    private $mailerService;

    public function __construct(ServiceMailer $mailerService)
    {
        $this->mailerService = $mailerService;
    }

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
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();
    
       $candidatures = $entityManager->getRepository(Candidature::class)->findBy([
          'candidat' => $currentUser
       ]);
    
       return $this->render('candidature/listCandidatures.html.twig', [
            'candidatures' => $candidatures,
       ]);
    }  

 /*  #[Route('/listCandidatures', name: 'list_candidatures')]
public function listCandidature(EntityManagerInterface $entityManager, Request $request): Response
{
    /** @var \App\Entity\User $currentUser */
  /*   $currentUser = $this->getUser();
    
    if (!$currentUser) {
        $this->addFlash('error', 'Vous devez être connecté pour voir vos candidatures.');
        return $this->redirectToRoute('app_login');
    }
    
    // On peut ajouter un filtre optionnel par statut
    $statut = $request->query->get('statut');
    
    // Utiliser la méthode dédiée du repository
    $candidatures = $entityManager->getRepository(Candidature::class)
        ->findByUser($currentUser, $statut);
    
    return $this->render('candidature/listCandidatures.html.twig', [
        'candidatures' => $candidatures,
    ]);
}  */ 
    

     #[Route('/addCandidature/{offre_id}', name: 'app_candidature_neww',methods: ['GET', 'POST'])]
public function newCandidatureoffre(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, 
#[Autowire('%kernel.project_dir%/public/uploads/cv')] string $cvDirectory,
#[Autowire('%kernel.project_dir%/public/uploads/motivation')] string $motivationDirectory,OffreemploiRepository $offreRepo, int $offre_id): Response
{
    // Récupération de l'utilisateur connecté
    $user = $this->getUser();
    if (!$user) {
        $this->addFlash('error', 'Vous devez être connecté pour postuler.');
        return $this->redirectToRoute('app_login'); // Route de connexion
    }
    
    $offre = $offreRepo->find($offre_id);
    if (!$offre) {
        $this->addFlash('error', 'Offre d\'emploi introuvable.');
        return $this->redirectToRoute('list_offres');  // Change vers la route des offres
    }
    $candidature = new Candidature();
    $form = $this->createForm(CandidatureType::class, $candidature);
    $candidature->setOffre($offre);
    $candidature->setCandidat($user);

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
            // $candidature->setOffre($offre);
            $cvFile = $form->get('cvFile')->getData();
        
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
                
                try {
                    $cvFile->move(
                        $cvDirectory,
                        $newFilename
                    );
                    // On stocke le nom du fichier dans la propriété cvUrl
                    $candidature->setcvUrl($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du CV');
                    return $this->redirectToRoute('app_candidature_new', ['offre_id' => $offre_id]);
                }
            }
            $lettreFile = $form->get('lettreMotivationFile')->getData();
        
        if ($lettreFile) {
            $originalFilename = pathinfo($lettreFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$lettreFile->guessExtension();
            
            try {
                $lettreFile->move(
                    $motivationDirectory,
                    $newFilename
                );
                $candidature->setLettreMotivation($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de la lettre de motivation');
                return $this->redirectToRoute('app_candidature_new', ['offre_id' => $offre_id]);
            }
        }
            $candidature->setStatut(Statut::EN_COURS);
            $candidature->setDateCandidature(new \DateTime());
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
#[Route('/addCandidature', name: 'app_candidature_new')]
public function newCandidature(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, 
#[Autowire('%kernel.project_dir%/public/uploads/cv')] string $cvDirectory,
#[Autowire('%kernel.project_dir%/public/uploads/motivation')] string $motivationDirectory): Response
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
            $cvFile = $form->get('cvFile')->getData();
        
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
                
                try {
                    $cvFile->move(
                        $cvDirectory,
                        $newFilename
                    );
                    // On stocke le nom du fichier dans la propriété cvUrl
                    $candidature->setcvUrl($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du CV');
                    return $this->redirectToRoute('app_candidature_new');
                }
            }
            $lettreFile = $form->get('lettreMotivationFile')->getData();
        
        if ($lettreFile) {
            $originalFilename = pathinfo($lettreFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$lettreFile->guessExtension();
            
            try {
                $lettreFile->move(
                    $motivationDirectory,
                    $newFilename
                );
                $candidature->setLettreMotivation($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de la lettre de motivation');
                return $this->redirectToRoute('app_candidature_new');
            }
        }
            $candidature->setStatut(Statut::EN_COURS);
            $candidature->setDateCandidature(new \DateTime());
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
public function editCandidature(Request $request, Candidature $candidature, EntityManagerInterface $entityManager,SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/cv')] string $cvDirectory,
#[Autowire('%kernel.project_dir%/public/uploads/motivation')] string $motivationDirectory): Response
{
    $oldCvFilename = $candidature->getCvUrl();
    $oldLettreFilename = $candidature->getLettreMotivation();
    $form = $this->createForm(CandidatureType::class, $candidature, [
        'include_offre' => true,
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $cvFile = $form->get('cvFile')->getData();
        
        if ($cvFile) {
            $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
            
            try {
                $cvFile->move(
                    $cvDirectory,
                    $newFilename
                );
                
                // Si un ancien fichier existait, on peut le supprimer
                if ($oldCvFilename && file_exists($cvDirectory.'/'.$oldCvFilename)) {
                    unlink($cvDirectory.'/'.$oldCvFilename);
                }
                
                // Mise à jour du nom du fichier dans l'entité
                $candidature->setcvUrl($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du CV');
            }
        } else {
            // Si aucun nouveau fichier n'est téléchargé, restaurer l'ancien nom de fichier
            // pour éviter qu'il soit effacé lors du flush
            $candidature->setcvUrl($oldCvFilename);
        }
        $lettreFile = $form->get('lettreMotivationFile')->getData();
        
        if ($lettreFile) {
            $originalFilename = pathinfo($lettreFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$lettreFile->guessExtension();
            
            try {
                $lettreFile->move(
                    $motivationDirectory,
                    $newFilename
                );
                
                // Si un ancien fichier existait, on peut le supprimer
                if ($oldLettreFilename && file_exists($motivationDirectory.'/'.$oldLettreFilename)) {
                    unlink($motivationDirectory.'/'.$oldLettreFilename);
                }
                
                // Mise à jour du nom du fichier dans l'entité
                $candidature->setLettreMotivation($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de la lettre de motivation');
            }
        } else {
            // Si aucun nouveau fichier n'est téléchargé, restaurer l'ancien nom de fichier
            $candidature->setLettreMotivation($oldLettreFilename);
        }
        
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été modifiée avec succès.');

        return $this->redirectToRoute('list_candidatures', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('candidature/editcandidature.html.twig', [
        'candidature' => $candidature,
        'form' => $form->createView(),
    ]);
}
#[Route('/candidature/{id}/delete', name: 'app_candidature_delete', methods: ['POST'], requirements: ['id' => '\d+'])]

public function deleteCandidature(Request $request, Candidature $candidature, EntityManagerInterface $entityManager,
#[Autowire('%kernel.project_dir%/public/uploads/cv')] string $cvDirectory,
    #[Autowire('%kernel.project_dir%/public/uploads/motivation')] string $motivationDirectory): Response
{
    if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->request->get('_token'))) {
        $cvFilename = $candidature->getCvUrl();
        if ($cvFilename && file_exists($cvDirectory.'/'.$cvFilename)) {
            unlink($cvDirectory.'/'.$cvFilename);
        }
        
        // Suppression du fichier lettre de motivation associé
        $lettreFilename = $candidature->getLettreMotivation();
        if ($lettreFilename && file_exists($motivationDirectory.'/'.$lettreFilename)) {
            unlink($motivationDirectory.'/'.$lettreFilename);
        }
        $entityManager->remove($candidature);
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été supprimée avec succès.');
    }

    return $this->redirectToRoute('list_candidatures', [], Response::HTTP_SEE_OTHER);
}


/* #[Route('/listCandidaturesrh', name: 'list_candidaturesrh', methods: ['GET'])]
public function listcandidaturerh(EntityManagerInterface $entityManager): Response
{
    $candidatures = $entityManager->getRepository(Candidature::class)
    
        ->findBy(['statut' => Statut::EN_COURS]);

    return $this->render('candidature/listCandidaturesrh.html.twig', [
        'candidatures' => $candidatures,
    ]);
} */
#[Route('/listCandidaturesrh', name: 'list_candidaturesrh', methods: ['GET'])]
public function listcandidaturerh(Request $request, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(FiltreCandidatureType::class);
    $form->handleRequest($request);

    $candidatureRepository = $entityManager->getRepository(Candidature::class);
    
    $offre = null;
    $candidatures = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $filtres = $form->getData();
        $offre = $filtres['offre'] ?? null;

        // Requête avec statut = EN_COURS + offre (si sélectionnée)
        $candidatures = $candidatureRepository->findByStatutAndOffre(Statut::EN_COURS, $offre);
    } else {
        // Par défaut : toutes les candidatures EN_COURS
        $candidatures = $candidatureRepository->findBy(['statut' => Statut::EN_COURS]);
    }

    return $this->render('candidature/listCandidaturesrh.html.twig', [
        'candidatures' => $candidatures,
        'filtreForm' => $form->createView(),
    ]);
}


#[Route('/listCandidatureArchivées', name: 'list_candidaturesarchivées')]
public function listCandidatureArchivées(EntityManagerInterface $entityManager): Response
{
    $qb = $entityManager->createQueryBuilder();

    $candidatures = $qb->select('c')
        ->from(Candidature::class, 'c')
        ->where('c.statut != :statut')
        ->setParameter('statut', \App\Enum\Statut::EN_COURS)
        ->getQuery()
        ->getResult();

    return $this->render('candidature/listCandidaturesArchivées.html.twig', [
        'candidatures' => $candidatures,
    ]);
}


#[Route('/candidature/{id}/accepter', name: 'candidature_accepter')]
public function accepter(Candidature $candidature, EntityManagerInterface $em): Response
{
    $candidature->setStatut(Statut::ACCEPTEE);
    $em->flush();
    $this->mailerService->sendAcceptationEmail(
        $candidature->getEmail(), // L'email du candidat
        $candidature->getPrenom()    // Le nom du candidat
    );

    return $this->redirectToRoute('list_candidaturesrh'); // adapte selon ton nom de route
}

#[Route('/candidature/{id}/refuser', name: 'candidature_refuser')]
public function refuser(Candidature $candidature, EntityManagerInterface $em): Response
{
    $candidature->setStatut(Statut::DISQUALIFIEE);
    $em->flush();
    $this->mailerService->sendRefusEmail(
        $candidature->getEmail(), // L'email du candidat
        $candidature->getPrenom()    // Le nom du candidat
    );

    return $this->redirectToRoute('list_candidaturesrh');
}






}
