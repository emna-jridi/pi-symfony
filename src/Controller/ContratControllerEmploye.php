<?php
namespace App\Controller;

use App\Entity\ContratEmploye;
use App\Form\ContratEmp;
use App\Enum\Typecontrat;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContratEmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ExcelExportContratsEmployes;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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







    /*//afficher la liste des contrats
    #[Route('/listEmp', name: 'list_emp')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les contrats depuis la base de données
        $contrats = $entityManager->getRepository(ContratEmploye::class)->findAll();

        // Affichage de la liste des contrats
        return $this->render('back_office/Contrats/listContratsEmploye.html.twig', [
            'contrats' => $contrats,
        ]);
    }*/






   //afficher liste des contrats des employés 
   #[Route('/listEmp', name: 'list_emp')]
   public function list(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
   {
       // Récupérer la requête pour tous les contrats
       $query = $entityManager->getRepository(ContratEmploye::class)
           ->createQueryBuilder('c')
           ->getQuery();
   
       // Utiliser le Paginator
       $pagination = $paginator->paginate(
           $query,
           $request->query->getInt('page', 1),
           4 
       );
       return $this->render('back_office/Contrats/listContratsEmploye.html.twig', [
           'pagination' => $pagination, 
       ]);
   }



//afficher détails du contrat
#[Route('/Contratemp/{idContratEmp}', name: 'contrat_show')]
public function show(int $idContratEmp, ContratEmployeRepository $contratEmployeRepository): Response
{
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
    
        $form = $this->createForm(ContratEmp::class, $contrat, [
            'is_edit' => true, 
        ]);
      
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($contrat);
            $entityManager->flush();
    
            $this->addFlash('success', 'Le contrat a été modifié avec succès.');
    
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

        $this->entityManager->remove($contrat);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_emp');
    }








//voir mon contrat en tant que employé
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




    
//recherche contrat d'un employé par ajax selon nom de l'employé
#[Route('/search-contrats-employe', name: 'search_contrats_employe', methods: ['GET'])]
public function searchContratsEmploye(Request $request, ContratEmployeRepository $contratRepository, PaginatorInterface $paginator): Response
{
    $term = $request->query->get('term');

    $query = $contratRepository->createQueryBuilder('c')
        ->join('c.user', 'u') 
        ->where('u.nomUser LIKE :term OR u.prenomUser LIKE :term')
        ->setParameter('term', '%' . $term . '%')
        ->getQuery();

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        4 
    );

    return $this->render('back_office/Contrats/searchcontratemploye.html.twig', [
        'pagination' => $pagination,
    ]);
}





   








//contrat employé pdf
#[Route('/contratt/pdf/{idContratEmp}', name: 'contrat_pdf')]
public function generateContratPdf(
    int $idContratEmp,
    EntityManagerInterface $entityManager,
    Pdf $knpSnappyPdf,
    \App\GoogleDriveUploader $googleDriveUploader
): Response
{
    // Récupérer le contrat par son ID
    $contrat = $entityManager->getRepository(ContratEmploye::class)->find($idContratEmp);

    if (!$contrat) {
        throw $this->createNotFoundException('Contrat non trouvé.');
    }

    // Générer le HTML à partir d'un template Twig
    $html = $this->renderView('back_office/Contrats/pdfcontratemploye.html.twig', [
        'contrat' => $contrat,
    ]);

    $nom = $contrat->getUser()->getNomUser();
    $prenom = $contrat->getUser()->getPrenomUser();
    $nomComplet = $nom . '_' . $prenom;
    $nomFichier = 'contrat_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $nomComplet) . '.pdf';

    // Générer le PDF avec l'option pour activer l'accès local aux fichiers
    $options = [
        'enable-local-file-access' => true,
    ];

    // Générer le PDF
    $pdfContent = $knpSnappyPdf->getOutputFromHtml($html, $options);
  
    $tempPath = $this->getParameter('kernel.project_dir') . '/var/' . $nomFichier;

    file_put_contents($tempPath, $pdfContent);

    // Uploader vers Google Drive
    // Utiliser l'instance injectée au lieu d'en créer une nouvelle
    $uploader = $googleDriveUploader;

    // L'ID du dossier où le fichier sera téléchargé
    $folderId = '1F4FC2ROd_OUtzr7UQS5iOkrWMhPLKqON'; // Remplacez par l'ID de votre dossier

    try {
        $fileId = $uploader->upload($tempPath, $nomFichier, $folderId);
        $message = "Fichier envoyé sur Google Drive (ID: $fileId)";
    } catch (\Exception $e) {
        $message = "Erreur d'envoi vers Drive: " . $e->getMessage();
    }

    // Retourner le PDF à l'utilisateur
    return new Response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $nomFichier . '"',
        // Nettoyer tous les caractères problématiques (retour à la ligne, tabulations, etc.)
        'X-Drive-Status' => preg_replace('/[\r\n\t]+/', ' ', $message),
    ]);    
}








    
      
//exporter contrats clients en fichier excel
#[Route('/contratsEmp/export', name: 'contratsemp_export')]
public function export(ExcelExportContratsEmployes $excelExportContratsEmployes , ContratEmployeRepository $contratEmployeRepository)
{
    $contrats = $contratEmployeRepository->findAll();
    $filePath = $excelExportContratsEmployes->exportContratsToExcel($contrats);

    return (new BinaryFileResponse($filePath))
        ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'contratsEmployes.xlsx');
}




}
