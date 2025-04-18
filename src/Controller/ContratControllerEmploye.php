<?php
namespace App\Controller;

use App\Entity\ContratEmploye;
use App\Form\ContratEmp;
use App\Enum\Typecontrat;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContratEmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ContratControllerEmploye extends AbstractController
{

      // utilise l'injection de dépendance pour recevoir une instance de EntityManagerInterface, qui permet d'interagir avec la base de données via Doctrine.
      private EntityManagerInterface $entityManager;

      public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }





    //ajouter contrat Employe
    #[Route('/addEmp', name: 'add_emp', methods: ['GET', 'POST'])]
   
public function add(Request $request, EntityManagerInterface $entityManager): Response
{
    $contratemploye = new ContratEmploye();
    $form = $this->createForm(ContratEmp::class, $contratemploye);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        if ($contratemploye->gettypecontrat() === Typecontrat::CDI) {
            $contratemploye->setDateFinContrat(null); 
        }
        // Save the contrat entity first
        $entityManager->persist($contratemploye);
        $entityManager->flush();

        // Redirection after success
        return $this->redirectToRoute('list_emp');
    }

    return $this->render('back_office/Contrats/addContratEmploye.html.twig', [
        'form' => $form->createView(),
    ]);
}







    //afficher la liste des contrats
    #[Route('/listEmp', name: 'list_emp')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les contrats depuis la base de données
        $contrats = $entityManager->getRepository(ContratEmploye::class)->findAll();

        // Affichage de la liste des contrats
        return $this->render('back_office/Contrats/listContratsEmploye.html.twig', [
            'contrats' => $contrats,
        ]);
    }




//afficher détails du contrat
#[Route('/Contratemp/{idContratEmp}', name: 'contrat_show')]
public function show(int $idContratEmp, ContratEmployeRepository $contratEmployeRepository): Response
{
    // Utilise la méthode findOneByIdContratEmp() pour récupérer le contrat
    $contrat = $contratEmployeRepository->findOneByIdContratEmp($idContratEmp);

    if (!$contrat) {
        throw new NotFoundHttpException('Contrat non trouvé');
    }

    return $this->render('back_office/Contrats/showContratsEmploye.html.twig', [
        'contrat' => $contrat,
    ]);
}

    //modifier contrat
    #[Route('/Contratemp/{idContratEmp}/edit', name: 'contrat_edit')]
    public function edit(Request $request, ContratEmploye $contrat, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'employé lié au contrat
        $user = $contrat->getUser();  
    
        // Créer le formulaire à partir de l'entité Contrat
        $form = $this->createForm(ContratEmp::class, $contrat, [
            'is_edit' => true, 
        ]);
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications du contrat
            $entityManager->persist($contrat);
            $entityManager->flush();
    
            // Ajouter un message flash pour notifier l'utilisateur
            $this->addFlash('success', 'Le contrat a été modifié avec succès.');
    
            // Rediriger vers la page de détail du contrat
            return $this->redirectToRoute('contrat_show', ['idContratEmp' => $contrat->getIdContratEmp()]);
        }
    
        return $this->render('back_office/Contrats/modifContratEmploye.html.twig', [
            'form' => $form->createView(),
            'contrat' => $contrat,
            'user' => $user, 
        ]);
    }
    
    

    



    // supprimer contrat
    #[Route('/Contratemp/{idContratEmp}/delete', name: 'contrat_delete')]
    public function delete(int $idContratEmp, ContratEmployeRepository $contratEmployeRepository): RedirectResponse
    {
        // Find the contract by ID
        $contrat = $contratEmployeRepository->findOneByIdContratEmp($idContratEmp);

        if (!$contrat) {
            throw $this->createNotFoundException('Contrat non trouvé');
        }

        // Remove the contract
        $this->entityManager->remove($contrat);
        $this->entityManager->flush();

        // Redirect to the list of contracts after deletion
        return $this->redirectToRoute('list_emp');
    }


}
?>