<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TestAssignment;
use App\Entity\TestResult;
use App\Entity\Equipe;
use App\Entity\ContratService;
use App\Repository\UserRepository;
use App\Repository\TestAssignmentRepository;
use App\Repository\TestResultRepository;
use App\Repository\EquipeRepository;
use App\Repository\ContratServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ExcelExportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs pour diagnostiquer le problème
        $allUsers = $userRepository->findAll();
        
        // Essayer différentes variations du rôle pour voir ce qui fonctionne
        $usersByRole = [
            'ROLE_EMPLOYE' => $userRepository->findByRole('ROLE_EMPLOYE'),
            'employe' => $userRepository->findByRole('employe'),
            'EMPLOYE' => $userRepository->findByRole('EMPLOYE'),
            'role_employe' => $userRepository->findByRole('role_employe')
        ];
        
        // Utiliser la première liste non vide, ou tous les utilisateurs si aucune ne fonctionne
        $users = !empty($usersByRole['ROLE_EMPLOYE']) ? $usersByRole['ROLE_EMPLOYE'] : 
                (!empty($usersByRole['employe']) ? $usersByRole['employe'] : 
                (!empty($usersByRole['EMPLOYE']) ? $usersByRole['EMPLOYE'] : 
                (!empty($usersByRole['role_employe']) ? $usersByRole['role_employe'] : $allUsers)));
        
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'debug' => [
                'all_users' => $allUsers,
                'users_by_role' => $usersByRole
            ]
        ]);
    }

    #[Route('/admin/users/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user): Response
    {
        // Cette méthode sera implémentée plus tard
        return $this->render('user/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/{id}/toggle-status', name: 'app_user_toggle_status', methods: ['POST'])]
    public function toggleStatus(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle_status'.$user->getIdUser(), $request->request->get('_token'))) {
            try {
                // Inverser le statut actif/inactif
                $user->setIsActive(!$user->getIsActive());
                $entityManager->flush();
                
                $message = $user->getIsActive() ? 
                    'L\'utilisateur a été activé avec succès.' : 
                    'L\'utilisateur a été désactivé avec succès.';
                
                $this->addFlash('success', $message);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification du statut : ' . $e->getMessage());
                error_log('Erreur lors de la modification du statut : ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }
        
        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/admin/users/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            try {
                // Supprimer l'utilisateur
                $entityManager->remove($user);
                $entityManager->flush();
                
                $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur : ' . $e->getMessage());
                // Log l'erreur pour le débogage
                error_log('Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }
        
        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/rh', name: 'app_rh_list')]
    public function rhList(UserRepository $userRepository): Response
    {
        $responsables = $userRepository->findByRole('ResponsableRH');
        
        return $this->render('user/rh_list.html.twig', [
            'responsables' => $responsables
        ]);
    }

    #[Route('/rh/{id}/toggle-status', name: 'app_rh_toggle_status', methods: ['POST'])]
    public function toggleRhStatus(User $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getRole() !== 'ResponsableRH') {
            throw $this->createNotFoundException('Cet utilisateur n\'est pas un responsable RH');
        }

        $user->setIsActive(!$user->getIsActive());
        $entityManager->flush();

        $this->addFlash('success', 'Le statut du responsable RH a été modifié avec succès.');
        return $this->redirectToRoute('app_rh_list');
    }

    #[Route('/rh/{id}/delete', name: 'app_rh_delete', methods: ['POST'])]
    public function deleteRh(User $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getRole() !== 'ResponsableRH') {
            throw $this->createNotFoundException('Cet utilisateur n\'est pas un responsable RH');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Le responsable RH a été supprimé avec succès.');
        return $this->redirectToRoute('app_rh_list');
    }

    #[Route('/export', name: 'app_user_export', methods: ['GET'])]
    public function export(UserRepository $userRepository, ExcelExportService $excelExportService): BinaryFileResponse
    {
        $users = $userRepository->findAll();
        $filePath = $excelExportService->exportUsers($users);

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'liste_employes_' . date('Y-m-d_His') . '.xlsx'
        );

        return $response;
    }
} 