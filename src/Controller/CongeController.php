<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Form\CongeTypeadmin;
use App\Repository\CongeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/conge')]
final class CongeController extends AbstractController
{
    private $security;
    private $currentUserId = 12;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_conge_index', methods: ['GET'])]
    public function index(Request $request, CongeRepository $congeRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // Récupère tous les congés
        $queryBuilder = $congeRepository->createQueryBuilder('c');

        // Fetch distinct congé types
        $types = $entityManager->createQueryBuilder()
            ->select('DISTINCT c.Type_conge')
            ->from(Conge::class, 'c')
            ->orderBy('c.Type_conge', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        // Pagination
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6 // Nombre d'éléments par page
        );

        return $this->render('conge/index.html.twig', [
            'conges' => $pagination,
            'currentUserId' => $this->currentUserId,
            'congeTypes' => $types,
        ]);
    }

    #[Route('/statistiques', name: 'app_conge_statistiques', methods: ['GET'])]
    public function statistiques(CongeRepository $congeRepository, EntityManagerInterface $entityManager): Response
    {
        // Statistiques par statut
        $conges = $congeRepository->findAll();
        $stats = [
            'En attente' => 0,
            'Accepté' => 0,
            'Refusé' => 0
        ];

        // Données pour les statistiques par type de congé
        $typesStats = [];
        // Données pour la durée moyenne par type
        $dureeMoyenneParType = [];
        // Données pour l'évolution mensuelle
        $evolutionMensuelle = [
            'En attente' => array_fill(0, 12, 0),
            'Accepté' => array_fill(0, 12, 0),
            'Refusé' => array_fill(0, 12, 0)
        ];

        $currentYear = (new \DateTime())->format('Y');

        foreach ($conges as $conge) {
            // Comptage par statut
            $status = $conge->getStatus();
            if (isset($stats[$status])) {
                $stats[$status]++;
            }

            // Comptage par type de congé
            $typeConge = $conge->getTypeConge();
            if (!isset($typesStats[$typeConge])) {
                $typesStats[$typeConge] = 0;
            }
            $typesStats[$typeConge]++;

            // Calcul de la durée du congé
            $dateDebut = $conge->getDateDebut();
            $dateFin = $conge->getDateFin();
            if ($dateDebut && $dateFin) {
                $duree = $dateDebut->diff($dateFin)->days + 1; // +1 car on compte le jour de début

                if (!isset($dureeMoyenneParType[$typeConge])) {
                    $dureeMoyenneParType[$typeConge] = ['total' => 0, 'count' => 0];
                }
                $dureeMoyenneParType[$typeConge]['total'] += $duree;
                $dureeMoyenneParType[$typeConge]['count']++;
            }

            // Statistiques mensuelles (uniquement pour l'année en cours)
            if ($dateDebut && $dateDebut->format('Y') == $currentYear) {
                $mois = intval($dateDebut->format('m')) - 1; // 0-indexed pour JavaScript
                $evolutionMensuelle[$status][$mois]++;
            }
        }

        // Calcul des moyennes pour la durée par type
        $moyenneDureeParType = [];
        foreach ($dureeMoyenneParType as $type => $data) {
            if ($data['count'] > 0) {
                $moyenneDureeParType[$type] = round($data['total'] / $data['count'], 1);
            } else {
                $moyenneDureeParType[$type] = 0;
            }
        }

        $total = array_sum($stats);
        $pourcentages = [];
        foreach ($stats as $status => $count) {
            $pourcentages[$status] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        }

        return $this->render('conge/statistiques.html.twig', [
            'stats' => $stats,
            'pourcentages' => $pourcentages,
            'total' => $total,
            'typesStats' => $typesStats,
            'moyenneDureeParType' => $moyenneDureeParType,
            'evolutionMensuelle' => $evolutionMensuelle
        ]);
    }
    #[Route('/mes-conges', name: 'app_mes_conges', methods: ['GET'])]
    public function mesConges(CongeRepository $congeRepository): Response
    {
        // Get the current logged-in user
        $user = $this->security->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $conges = $congeRepository->findBy(['id_user' => $user]);

        return $this->render('conge/index.html.twig', [
            'conges' => $conges,
            'user' => $user,
        ]);
    }

    #[Route('/search', name: 'app_conge_search', methods: ['GET'])]
    public function search(Request $request, CongeRepository $congeRepository): Response
    {
        try {
            $searchTerm = $request->query->get('type', '');

            // Search by both Type_conge and Status
            $queryBuilder = $congeRepository->createQueryBuilder('c')
                ->where('c.Type_conge LIKE :searchTerm')
                ->orWhere('c.Status LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');

            $conges = $queryBuilder->getQuery()->getResult();

            $congesData = array_map(function ($conge) {
                    return [
                        'id' => $conge->getId(),
                        'TypeConge' => $conge->getTypeConge(),
                        'DateDebut' => $conge->getDateDebut() ? $conge->getDateDebut()->format('Y-m-d') : null,
                        'DateFin' => $conge->getDateFin() ? $conge->getDateFin()->format('Y-m-d') : null,
                        'Status' => $conge->getStatus(),
                        'user' => $conge->getIdUser() ? [
                            'nomUser' => $conge->getIdUser()->getNomUser(),
                            'prenomUser' => $conge->getIdUser()->getPrenomUser()
                        ] : null
                    ];
            }, $conges);

            return new JsonResponse([
                'conges' => $congesData
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Une erreur est survenue lors de la recherche: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/user/{id}', name: 'app_conge_by_user', methods: ['GET'])]
    public function congesByUser(int $id, CongeRepository $congeRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $conges = $congeRepository->findBy(['id_user' => $user]);

        return $this->render('conge/index.html.twig', [
            'conges' => $conges,
            'user' => $user,
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $conge = new Conge();
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find($this->currentUserId);
            if ($user) {
                $conge->setIdUser($user);
            }

            $conge->setStatus('En attente');
            $entityManager->persist($conge);
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_show', methods: ['GET'])]
    public function show(Conge $conge): Response
    {
        return $this->render('conge/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_delete', methods: ['POST'])]
    public function delete(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit/admin', name: 'app_conge_edit_admin', methods: ['GET', 'POST'])]
    public function edit_admin(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongeTypeadmin::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldStatus = $conge->getStatus();
            $entityManager->flush();
            $newStatus = $conge->getStatus();

            if ($oldStatus !== $newStatus) {
                if ($newStatus === 'Accepté') {
                    $this->addFlash('conge_status', 'Votre congé a été accepté !');
                } elseif ($newStatus === 'Refusé') {
                    $this->addFlash('conge_status', 'Votre congé a été refusé.');
                }
            } else {
            $this->addFlash('success', 'Congé mis à jour avec succès !');
            }

            // Rediriger vers la page principale des congés avec un paramètre pour indiquer que le statut a été modifié
            return $this->redirectToRoute('app_conge_index', ['status_changed' => true], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit_admin.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route("/conge_approve/admin", name: 'app_conge_index_admin', methods: ['GET'])]
    public function indexadmin(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index_admin.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_conge_pdf', methods: ['GET'])]
    public function generatePdf(Conge $conge): Response
    {
        // Vérifier si le congé est accepté
        if ($conge->getStatus() !== 'Accepté') {
            throw $this->createAccessDeniedException('Seuls les congés acceptés peuvent être téléchargés en PDF.');
        }

        try {
            // Créer le contenu HTML du PDF avec un template simplifié
            $html = $this->renderView('conge/pdf.html.twig', [
                'conge' => $conge,
            ]);

            // Vérifier que le HTML n'est pas vide
            if (empty($html)) {
                throw new \RuntimeException('Le contenu HTML généré est vide.');
            }

            // Chemin vers wkhtmltopdf dans Program Files
            $wkhtmltopdf = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';

            // Vérifier si wkhtmltopdf existe
            if (!file_exists($wkhtmltopdf)) {
                throw new \RuntimeException(
                    'wkhtmltopdf n\'est pas installé ou le chemin est incorrect. ' .
                    'Veuillez vérifier que wkhtmltopdf est bien installé dans C:\\Program Files\\wkhtmltopdf'
                );
            }

            // Créer un fichier temporaire pour le HTML
            $tempDir = sys_get_temp_dir();
            $tempHtml = $tempDir . DIRECTORY_SEPARATOR . 'conge_' . uniqid() . '.html';
            $tempPdf = $tempDir . DIRECTORY_SEPARATOR . 'conge_' . uniqid() . '.pdf';

            // Écrire le HTML dans le fichier temporaire
            if (file_put_contents($tempHtml, $html) === false) {
                throw new \RuntimeException('Impossible d\'écrire dans le fichier HTML temporaire.');
            }

            // Générer le PDF avec wkhtmltopdf avec des options simplifiées
            $command = sprintf(
                '"%s" --enable-local-file-access --no-images --disable-javascript --load-error-handling ignore --margin-top 20 --margin-right 20 --margin-bottom 20 --margin-left 20 --page-size A4 --orientation Portrait "%s" "%s"',
                $wkhtmltopdf,
                $tempHtml,
                $tempPdf
            );

            // Exécuter la commande avec la sortie d'erreur
            $descriptorspec = array(
                1 => array("pipe", "w"), // stdout
                2 => array("pipe", "w")  // stderr
            );

            $process = proc_open($command, $descriptorspec, $pipes);
            if (is_resource($process)) {
                $stdout = stream_get_contents($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                $returnVar = proc_close($process);
            } else {
                throw new \RuntimeException('Impossible d\'exécuter la commande wkhtmltopdf.');
            }

            // Vérifier si la commande a réussi
            if ($returnVar !== 0) {
                // Sauvegarder le HTML généré pour débogage
                $debugHtml = $tempDir . DIRECTORY_SEPARATOR . 'debug_conge.html';
                file_put_contents($debugHtml, $html);
                
                if (file_exists($tempHtml)) {
                    unlink($tempHtml);
                }
                if (file_exists($tempPdf)) {
                    unlink($tempPdf);
                }
                
                throw new \RuntimeException(
                    'Erreur lors de la génération du PDF. ' .
                    'Code de retour: ' . $returnVar . "\n" .
                    'Sortie standard: ' . $stdout . "\n" .
                    'Erreur standard: ' . $stderr . "\n" .
                    'Commande exécutée: ' . $command . "\n" .
                    'HTML généré sauvegardé dans: ' . $debugHtml
                );
            }

            // Vérifier que le fichier PDF a été créé
            if (!file_exists($tempPdf)) {
                if (file_exists($tempHtml)) {
                    unlink($tempHtml);
                }
                throw new \RuntimeException(
                    'Le fichier PDF n\'a pas été généré. ' .
                    'Commande exécutée: ' . $command
                );
            }

            // Lire le PDF généré
            $pdfContent = file_get_contents($tempPdf);
            if ($pdfContent === false) {
                if (file_exists($tempHtml)) {
                    unlink($tempHtml);
                }
                if (file_exists($tempPdf)) {
                    unlink($tempPdf);
                }
                throw new \RuntimeException(
                    'Impossible de lire le fichier PDF généré. ' .
                    'Chemin du fichier: ' . $tempPdf
                );
            }

            // Nettoyer les fichiers temporaires
            if (file_exists($tempHtml)) {
                unlink($tempHtml);
            }
            if (file_exists($tempPdf)) {
                unlink($tempPdf);
            }

            // Générer le nom du fichier
            $filename = sprintf('conge_%d_%s.pdf', $conge->getId(), date('Y-m-d'));

            // Retourner le PDF
            return new Response(
                $pdfContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]
            );
        } catch (\Exception $e) {
            // Nettoyer les fichiers temporaires en cas d'erreur
            if (isset($tempHtml) && file_exists($tempHtml)) {
                unlink($tempHtml);
            }
            if (isset($tempPdf) && file_exists($tempPdf)) {
                unlink($tempPdf);
            }
            
            throw new \RuntimeException(
                'Erreur lors de la génération du PDF: ' . $e->getMessage() . "\n" .
                'Fichier: ' . $e->getFile() . "\n" .
                'Ligne: ' . $e->getLine()
            );
        }
    }

    #[Route('/conge/{id}/details', name: 'app_conge_details', methods: ['GET'])]
    public function details(Conge $conge): JsonResponse
    {
        return $this->json([
            'id' => $conge->getId(),
            'TypeConge' => $conge->getTypeConge(),
            'DateDebut' => $conge->getDateDebut() ? $conge->getDateDebut()->format('Y-m-d') : null,
            'DateFin' => $conge->getDateFin() ? $conge->getDateFin()->format('Y-m-d') : null,
            'Status' => $conge->getStatus(),
            'user' => $conge->getIdUser() ? [
                'nomUser' => $conge->getIdUser()->getNomUser(),
                'prenomUser' => $conge->getIdUser()->getPrenomUser(),
            ] : null,
        ]);
    }
}