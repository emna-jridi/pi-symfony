<?php
namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\ContratService;
use App\Form\ContratType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ContratController extends AbstractController
{

      // utilise l'injection de dépendance pour recevoir une instance de EntityManagerInterface, qui permet d'interagir avec la base de données via Doctrine.
      private EntityManagerInterface $entityManager;

      public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }





    //ajouter contrat Client
    #[Route('/addC', name: 'add_c', methods: ['GET', 'POST'])]
public function add(Request $request, EntityManagerInterface $entityManager): Response
{
    $contrat = new Contrat();
    $form = $this->createForm(ContratType::class, $contrat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $services = $form->get('contratServices')->getData();  

        foreach ($services as $service) {
            $contratService = new ContratService();
            $contratService->setContrat($contrat);
            $contratService->setService($service);
            $entityManager->persist($contratService);
        }


        
        // Save the contrat entity first
        $entityManager->persist($contrat);
        $entityManager->flush();

        // Redirection after success
        return $this->redirectToRoute('list_c');
    }

    return $this->render('back_office/Contrats/addContrat.html.twig', [
        'form' => $form->createView(),
    ]);
}







    //afficher la liste des contrats
    #[Route('/listC', name: 'list_c')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les contrats depuis la base de données
        $contrats = $entityManager->getRepository(Contrat::class)->findAll();

        // Affichage de la liste des contrats
        return $this->render('back_office/Contrats/listContrats.html.twig', [
            'contrats' => $contrats,
        ]);
    }




//afficher détails du contrat
    #[Route('/Contrats/{idContrat}', name: 'contratt_show')]
    public function show(int $idContrat, ContratRepository $contratRepository): Response
    {
        // Récupérer le contrat avec l'ID donné
        $contrat = $contratRepository->findOneByIdContrat($idContrat);

        if (!$contrat) {
            throw new NotFoundHttpException('Contrat non trouvé');
        }

        return $this->render('back_office/Contrats/showContrats.html.twig', [
            'contrat' => $contrat,
            'services' => $contrat->getContratServices(),
        ]);
    }


    //modifier contrat
    #[Route('/Contrats/{idContrat}/edit', name: 'contratt_edit')]
public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
{
    // Créer le formulaire à partir de l'entité Contrat
    $form = $this->createForm(ContratType::class, $contrat);

    // Pré-sélectionner les services associés au contrat
    // Récupérer les services associés au contrat via ContratService
    $form->get('contratServices')->setData($contrat->getServices());

    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer les services sélectionnés depuis le formulaire
        $contratServices = $form->get('contratServices')->getData();

        // Vider les services existants associés au contrat (si nécessaire)
        foreach ($contrat->getContratServices() as $existingContratService) {
            $contrat->removeContratService($existingContratService);
        }

        // Associer les nouveaux services au contrat via ContratService
        foreach ($contratServices as $service) {
            // Vérifier si le service est déjà associé au contrat
            $existingContratService = $entityManager->getRepository(ContratService::class)->findOneBy([
                'contrat' => $contrat,
                'service' => $service
            ]);

            if (!$existingContratService) {
                // Si la relation n'existe pas déjà, créer un nouvel objet ContratService
                $contratService = new ContratService();
                $contratService->setContrat($contrat);
                $contratService->setService($service);

                // Ajouter l'objet ContratService au contrat
                $contrat->addContratService($contratService);

                // Persister l'objet ContratService
                $entityManager->persist($contratService);
            }
        }

        

        // Sauvegarder les modifications du contrat et des services associés
        $entityManager->persist($contrat);
        $entityManager->flush();

        // Ajouter un message flash pour notifier l'utilisateur
        $this->addFlash('success', 'Le contrat a été modifié avec succès.');

        // Rediriger vers la page de détail du contrat
        return $this->redirectToRoute('contratt_show', ['idContrat' => $contrat->getIdContrat()]);
    }

    return $this->render('back_office/Contrats/modifContrat.html.twig', [
        'form' => $form->createView(),
        'contrat' => $contrat,
    ]);
}

    

    



    // supprimer contrat
    #[Route('/Contrats/{idContrat}/delete', name: 'contratt_delete')]
    public function delete(int $idContrat, ContratRepository $contratRepository): RedirectResponse
    {
        // Find the contract by ID
        $contrat = $contratRepository->findOneByIdContrat($idContrat);

        if (!$contrat) {
            throw $this->createNotFoundException('Contrat non trouvé');
        }

        // Remove the contract
        $this->entityManager->remove($contrat);
        $this->entityManager->flush();

        // Redirect to the list of contracts after deletion
        return $this->redirectToRoute('list_c');
    }


}
?>