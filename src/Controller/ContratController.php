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
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use App\ExcelExportContratsClients;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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







    /*//afficher la liste des contrats
    #[Route('/listC', name: 'list_c')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les contrats depuis la base de données
        $contrats = $entityManager->getRepository(Contrat::class)->findAll();

        // Affichage de la liste des contrats
        return $this->render('back_office/Contrats/listContrats.html.twig', [
            'contrats' => $contrats,
        ]);
    }*/




    #[Route('/listC', name: 'list_c')]
public function list(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
{
  
    $queryBuilder = $entityManager->getRepository(Contrat::class)->createQueryBuilder('c');

    $pagination = $paginator->paginate(
        $queryBuilder, 
        $request->query->getInt('page', 1),
        6 
    );

    return $this->render('back_office/Contrats/listContrats.html.twig', [
        'pagination' => $pagination,
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
   
    $form = $this->createForm(ContratType::class, $contrat);

    $form->get('contratServices')->setData($contrat->getServices());

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $contratServices = $form->get('contratServices')->getData();

        foreach ($contrat->getContratServices() as $existingContratService) {
            $contrat->removeContratService($existingContratService);
        }

        foreach ($contratServices as $service) {
            $existingContratService = $entityManager->getRepository(ContratService::class)->findOneBy([
                'contrat' => $contrat,
                'service' => $service
            ]);

            if (!$existingContratService) {
                $contratService = new ContratService();
                $contratService->setContrat($contrat);
                $contratService->setService($service);
                $contrat->addContratService($contratService);
                $entityManager->persist($contratService);
            }
        }
        $entityManager->persist($contrat);
        $entityManager->flush();

        $this->addFlash('success', 'Le contrat a été modifié avec succès.');

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
        $contrat = $contratRepository->findOneByIdContrat($idContrat);

        if (!$contrat) {
            throw $this->createNotFoundException('Contrat non trouvé');
        }

        $this->entityManager->remove($contrat);
        $this->entityManager->flush();

        return $this->redirectToRoute('list_c');
    }








    

//recherche contrat d'un client par ajax
#[Route('/search-contrats', name: 'search_contrats', methods: ['GET'])]
public function search(Request $request, PaginatorInterface $paginator, ContratRepository $contratRepository): Response
{
    $term = $request->query->get('term');

    $queryBuilder = $contratRepository->createQueryBuilder('c');

    if ($term) {
        $queryBuilder->where('c.NomClient LIKE :term')
                     ->setParameter('term', '%' . $term . '%');
    }

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        6 
    );

    return $this->render('back_office/Contrats/searchcontrat.html.twig', [
        'pagination' => $pagination,
    ]);
}













//contrat client pdf
#[Route('/contrat/pdf/{idContrat}', name: 'contratclient_pdf')]
public function generateContratPdf(
    int $idContrat,
    EntityManagerInterface $entityManager,
    Pdf $knpSnappyPdf
): Response {
    $contrat = $entityManager->getRepository(Contrat::class)->find($idContrat);

    if (!$contrat) {
        throw $this->createNotFoundException('Contrat non trouvé.');
    }

    // Génération HTML
    $html = $this->renderView('back_office/Contrats/pdfcontratclient.html.twig', [
        'contrat' => $contrat,
    ]);

    $options = ['enable-local-file-access' => true];

    // Générer le PDF
    $pdfContent = $knpSnappyPdf->getOutputFromHtml($html, $options);

    // Enregistrer temporairement le fichier
    $filename = $contrat->getNomClient() . '.pdf';
    $tempPath = $this->getParameter('kernel.project_dir') . '/var/' . $filename;

    file_put_contents($tempPath, $pdfContent);

    //Uploader vers Google Drive
    $uploader = new \App\GoogleDriveUploader();

    // L'ID du dossier où le fichier sera téléchargé
    $folderId = '1LsUGNYV8V_Fd28TuGPBbxR1fJFWdb-Jn'; 

    try {
        $fileId = $uploader->upload($tempPath, $filename, $folderId);
        $message = "Fichier envoyé sur Google Drive (ID: $fileId)";
    } catch (\Exception $e) {
        $message = "Erreur d'envoi vers Drive: " . $e->getMessage();
    }
//Retourner le PDF à l'utilisateur
return new Response($pdfContent, 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'inline; filename="' . $filename . '"',
    // Nettoyer tous les caractères problématiques (retour à la ligne, tabulations, etc.)
    'X-Drive-Status' => preg_replace('/[\r\n\t]+/', ' ', $message),
]);
}






    



//exporter contrats clients en fichier excel
    #[Route('/contratsCl/export', name: 'contrats_export')]
    public function export(ExcelExportContratsClients $excelExportService, ContratRepository $contratRepository)
    {
        $contrats = $contratRepository->findAll();
        $filePath = $excelExportService->exportContratsToExcel($contrats);
    
        return (new BinaryFileResponse($filePath))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'contratsClients.xlsx');
    }











}
