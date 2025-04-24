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
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Snappy\Pdf;
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









    #[Route('/mon-contrat', name: 'mon_contrat')]
    public function monContrat(ContratEmployeRepository $repository): Response
    {
        $user = $this->getUser();
        $contrat = $repository->findOneByUser($user);
    
        if (!$contrat) {
            $this->addFlash('warning', 'Aucun contrat trouvé pour vous.');
 
        }
    
        return $this->render('back_office/Contrats/moncontrat.html.twig', [
            'contrat' => $contrat,
        ]);
    }













    
//recherche contrat d'un employé par ajax
#[Route('/search-contrats-employe', name: 'search_contrats_employe', methods: ['GET'])]
public function searchContratsEmploye(Request $request, ContratEmployeRepository $contratRepository): Response
{
    $term = $request->query->get('term');

    $contrats = $contratRepository->createQueryBuilder('c')
        ->join('c.user', 'u') 
        ->where('u.nomUser LIKE :term OR u.prenomUser LIKE :term')
        ->setParameter('term', '%' . $term . '%')
        ->getQuery()
        ->getResult();

    return $this->render('back_office/Contrats/searchcontratemploye.html.twig', [
        'contrats' => $contrats,
    ]);
}







//contrat employé pdf
#[Route('/contratt/pdf/{idContratEmp}', name: 'contrat_pdf')]
    public function generateContratPdf(int $idContratEmp, EntityManagerInterface $entityManager, Pdf $knpSnappyPdf): Response
    {
        // Récupérer le contrat par son ID
        $contrat = $entityManager->getRepository(ContratEmploye::class)->find($idContratEmp);

        if (!$contrat) {
            throw $this->createNotFoundException('Contrat non trouvé.');
        }

        // Générer le HTML à partir d’un template Twig
        $html = $this->renderView('back_office/Contrats/pdfcontratemploye.html.twig', [
            'contrat' => $contrat,
        ]);

        $nom = $contrat->getUser()->getNomUser();
        $prenom = $contrat->getUser()->getPrenomUser();
        $nomComplet = $nom . '_' . $prenom;
        $nomFichier = 'contrat_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $nomComplet) . '.pdf';

        // Générer le PDF
        $pdfContent = $knpSnappyPdf->getOutputFromHtml($html);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$nomFichier.'"',
        ]);
    }







/*contrat pdf
#[Route('/contratt/pdf/{idContratEmp}', name: 'contrat_pdf')]
public function generatePdf(ContratEmployeRepository $repository, int $idContratEmp): Response
{
    $contrat = $repository->find($idContratEmp);

    if (!$contrat) {
        throw $this->createNotFoundException('Contrat non trouvé.');
    }

    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    $html = $this->renderView('back_office/Contrats/pdfcontratemploye.html.twig', [
        'contrat' => $contrat
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();


    $nom = $contrat->getUser()->getNomUser();
    $prenom = $contrat->getUser()->getPrenomUser();
    $nomComplet = $nom . '_' . $prenom;
    $nomFichier = 'contrat_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $nomComplet) . '.pdf';

    return new Response(
        $dompdf->output(),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomFichier . '"',

        ]
    );
}*/





















    
    
    


}
