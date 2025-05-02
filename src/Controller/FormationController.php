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
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/formation')]
final class FormationController extends AbstractController{

    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 1;
        
        $keyword = $request->query->get('keyword', '');
        $theme = $request->query->get('theme');
        $niveau = $request->query->get('niveau');
        $sort = $request->query->get('sort', 'idFormation');
        $direction = $request->query->get('direction', 'DESC');

        $filters = [
            'theme' => $theme,
            'niveau' => $niveau
        ];

        $criteria = [
            'sort' => $sort,
            'direction' => $direction
        ];

        try {
            $queryBuilder = $formationRepository->createQueryBuilderWithFilters($keyword, $criteria, $filters);
            
            $pagination = $paginator->paginate(
                $queryBuilder,
                $page,
                $limit,
                [
                    'defaultSortFieldName' => 'f.idFormation',
                    'defaultSortDirection' => 'DESC',
                    'distinct' => false
                ]
            );

            if ($request->isXmlHttpRequest()) {
                $formations = [];
                foreach ($pagination->getItems() as $formation) {
                    $formations[] = [
                        'idFormation' => $formation->getIdFormation(),
                        'NomFormation' => $formation->getNomFormation(),
                        'ThemeFormation' => $formation->getThemeFormation(),
                        'niveauDifficulte' => $formation->getNiveauDifficulte(),
                        'duree' => $formation->getDuree(),
                        'date' => $formation->getDate() ? $formation->getDate()->format('Y-m-d') : null,
                        'urls' => [
                            'show' => $this->generateUrl('app_formation_show', ['idFormation' => $formation->getIdFormation()]),
                            'edit' => $this->generateUrl('app_formation_edit', ['idFormation' => $formation->getIdFormation()]),
                            'delete' => $this->generateUrl('app_formation_delete', ['idFormation' => $formation->getIdFormation()])
                        ]
                    ];
                }

                return new JsonResponse([
                    'formations' => $formations,
                    'currentPage' => $page,
                    'lastPage' => ceil($pagination->getTotalItemCount() / $limit),
                    'totalItems' => $pagination->getTotalItemCount()
                ]);
            }

            return $this->render('formation/index.html.twig', [
                'formations' => $pagination,
                'currentPage' => $page,
                'lastPage' => ceil($pagination->getTotalItemCount() / $limit),
                'totalItems' => $pagination->getTotalItemCount()
            ]);
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'error' => 'Une erreur s\'est produite lors de la recherche.',
                    'details' => $e->getMessage()
                ], 500);
            }
            
            return $this->render('formation/index.html.twig', [
                'formations' => [],
                'currentPage' => 1,
                'lastPage' => 1,
                'totalItems' => 0,
                'error' => 'Une erreur s\'est produite lors de la recherche : ' . $e->getMessage()
            ]);
        }
    }
    
    
    #[Route('/formations',name: 'formationEmp', methods: ['GET'])]
    public function indexEmp(Request $request, FormationRepository $formationRepository, PaginatorInterface $paginator): Response
    {
        $keyword = $request->query->get('keyword', '');
        $sort = $request->query->get('sort', 'idFormation');
        $direction = $request->query->get('direction', 'DESC');
        $filters = [
            'theme' => $request->query->get('theme'),
            'niveau' => $request->query->get('niveau'),
            'date' => $request->query->get('date'),
        ];

        $criteria = ['sort' => $sort, 'direction' => $direction];
        
        // Get formations based on search and filters
        $query = $formationRepository->createQueryBuilderWithFilters($keyword, $criteria, $filters);
        
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Items per page
        
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit,
            [
                'defaultSortFieldName' => 'f.idFormation',
                'defaultSortDirection' => 'DESC',
                'distinct' => false
            ]
        );

        if ($request->isXmlHttpRequest()) {
            $formations = [];
            foreach ($pagination->getItems() as $formation) {
                $formations[] = [
                    'idFormation' => $formation->getIdFormation(),
                    'nomFormation' => $formation->getNomFormation(),
                    'themeFormation' => $formation->getThemeFormation(),
                    'niveauDifficulte' => $formation->getNiveauDifficulte(),
                    'duree' => $formation->getDuree(),
                    'date' => $formation->getDate() ? $formation->getDate()->format('Y-m-d') : null,
                    'description' => $formation->getDescription(),
                    'lienFormation' => $formation->getLienFormation()
                ];
            }

            return new JsonResponse([
                'formations' => $formations,
                'pagination' => [
                    'currentPage' => $page,
                    'lastPage' => ceil($pagination->getTotalItemCount() / $limit),
                    'totalItems' => $pagination->getTotalItemCount()
                ]
            ]);
        }

        return $this->render('formation/formationEmp.html.twig', [
            'formations' => $pagination->getItems(),
            'pagination' => $pagination,
            'currentPage' => $page,
            'lastPage' => ceil($pagination->getTotalItemCount() / $limit),
            'totalItems' => $pagination->getTotalItemCount()
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
    #[Route('/formations/detail/{id}', name: 'formationEmpShow', methods: ['GET'])]
    public function showEmp(Formation $formation): Response
    {
        return $this->render('formation/formationEmpShow.html.twig', [
            'formation' => $formation
        ]);
    }
}