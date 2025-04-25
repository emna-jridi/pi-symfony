<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/formation')]
final class FormationController extends AbstractController{

    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {
        $keyword = $request->query->get('keyword', '');
        $sort = $request->query->get('sort', '');
        $direction = $request->query->get('direction', 'ASC');
        $filters = [
            'theme' => $request->query->get('theme'),
            'niveau' => $request->query->get('niveau'),
            'date' => $request->query->get('date'),
        ];

        $criteria = ['sort' => $sort, 'direction' => $direction];
        
        // Get formations based on search and filters
        $query = $formationRepository->createQueryBuilderWithFilters($keyword, $criteria, $filters);
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 // Nombre d'éléments par page
        );
        
        // If it's an AJAX request, return JSON response
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'formations' => array_map(function($formation) {
                    return [
                        'idFormation' => $formation->getIdFormation(),
                        'nomFormation' => $formation->getNomFormation(),
                        'themeFormation' => $formation->getThemeFormation(),
                        'niveauDifficulte' => $formation->getNiveauDifficulte(),
                        'duree' => $formation->getDuree(),
                        'date' => $formation->getDate() ? $formation->getDate()->format('Y-m-d') : null,
                        'description' => $formation->getDescription(),
                        'urls' => [
                            'show' => $this->generateUrl('app_formation_show', ['idFormation' => $formation->getIdFormation()]),
                            'edit' => $this->generateUrl('app_formation_edit', ['idFormation' => $formation->getIdFormation()]),
                            'delete' => $this->generateUrl('app_formation_delete', ['idFormation' => $formation->getIdFormation()]),
                        ]
                    ];
                }, $pagination->getItems()),
                'pagination' => [
                    'current' => $pagination->getCurrentPageNumber(),
                    'total' => $pagination->getTotalItemCount(),
                    'pages' => ceil($pagination->getTotalItemCount() / 10)
                ]
            ]);
        }
        
        return $this->render('formation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    
    #[Route('/formations',name: 'formationEmp', methods: ['GET'])]
    public function indexEmp(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {
        $keyword = $request->query->get('keyword', '');
        $sort = $request->query->get('sort', '');
        $direction = $request->query->get('direction', 'ASC');
        $filters = [
            'theme' => $request->query->get('theme'),
            'niveau' => $request->query->get('niveau'),
            'date' => $request->query->get('date'),
        ];

        $criteria = ['sort' => $sort, 'direction' => $direction];
        
        // Get formations based on search and filters
        $query = $formationRepository->createQueryBuilderWithFilters($keyword, $criteria, $filters);
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6 // Moins d'éléments par page pour la vue carte
        );

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'formations' => array_map(function($formation) {
                    return [
                        'idFormation' => $formation->getIdFormation(),
                        'nomFormation' => $formation->getNomFormation(),
                        'themeFormation' => $formation->getThemeFormation(),
                        'niveauDifficulte' => $formation->getNiveauDifficulte(),
                        'duree' => $formation->getDuree(),
                        'date' => $formation->getDate() ? $formation->getDate()->format('Y-m-d') : null,
                        'description' => $formation->getDescription(),
                        'lienFormation' => $formation->getLienFormation()
                    ];
                }, $pagination->getItems()),
                'pagination' => [
                    'current' => $pagination->getCurrentPageNumber(),
                    'total' => $pagination->getTotalItemCount(),
                    'pages' => ceil($pagination->getTotalItemCount() / 6)
                ]
            ]);
        }

        return $this->render('formation/formationEmp.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{idFormation}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/{idFormation}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{idFormation}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getIdFormation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }

}