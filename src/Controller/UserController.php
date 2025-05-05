<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TestAssignment;
use App\Entity\TestResult;
use App\Entity\Equipe;
use App\Entity\ContratService;
use App\Repository\UserRepository;
use App\Repository\ContratEmployeRepository;
use App\Repository\TestAssignmentRepository;
use App\Repository\TestResultRepository;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Repository\EquipeRepository;
use App\Repository\ContratServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
                $user->setIsActive(!$user->IsActive());
                $entityManager->flush();
                
                $message = $user->IsActive() ? 
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



























    //liste des employés
    #[Route('/employes', name: 'list_employe')]
    public function listEmployes(UserRepository $userRepository): Response
    {
        $employes = $userRepository->findByRole('Employe');
    
        
        return $this->render('back_office/Contrats/listemployés.html.twig', [
            'employes' => $employes,
        ]);
    }
    //voir contrat
    #[Route('/employe/{id}/contrat', name: 'app_employe_contrat')]
    public function contrat(User $user, ContratEmployeRepository $contratRepository): Response
    {
        $contrat = $contratRepository->findOneBy(['user' => $user]);
        if (!$contrat) {
      
            $this->addFlash('notice', 'Aucun contrat trouvé pour cet employé.');
    
            return $this->redirectToRoute('list_employe');
        }
        return $this->render('back_office/Contrats/showContratsEmploye.html.twig', [
            'contrat' => $contrat,
        ]);
    }
//supprimer l'employé et son contrat
    #[Route('/delete/{id}', name: 'app_user_delete_with_contract', methods: ['POST'])]
    public function deleteWithContract(
        Request $request,
        User $user,
        EntityManagerInterface $em,
        CsrfTokenManagerInterface $csrfTokenManager,
        ContratEmployeRepository $contratRepository
    ): Response {
        $submittedToken = $request->request->get('_token');
    
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_with_contract' . $user->getIdUser(), $submittedToken))) {
            $this->addFlash('error', 'Jeton CSRF invalide ');
            return $this->redirectToRoute('list_employe');
        }
        $contrat = $contratRepository->findOneBy(['user' => $user]);

        if ($contrat) {
            $em->remove($contrat);
        }
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', "Employé et son contrat supprimés avec succès. ");
        return $this->redirectToRoute('list_employe');
    }
    





    
} 