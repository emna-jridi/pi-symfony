<?php
// src/Controller/HRController.php
namespace App\Controller;

use App\Entity\TestTechnique;
use App\Entity\TestAssignment;
use App\Entity\User;
use App\Form\TestAssignmentType;
use App\Repository\TestAssignmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/hr')]
class HRController extends AbstractController
{

    #[Route('/dashboard', name: 'app_hr_dashboard', methods: ['GET'])]
    public function dashboard(TestAssignmentRepository $assignmentRepository): Response
    {
        // Ensure user has HR role
        $this->denyAccessUnlessGranted('ROLE_HR');
        
        // Get all test assignments
        $assignments = $assignmentRepository->findAll();
        
        return $this->render('hr/dashboard.html.twig', [
            'assignments' => $assignments,
        ]);
    }

    #[Route('/assign-test', name: 'app_hr_assign_test', methods: ['GET', 'POST'])]
    public function assignTest(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        // Ensure user has HR role
        $this->denyAccessUnlessGranted('ROLE_HR');
        
        $assignment = new TestAssignment();
        $form = $this->createForm(TestAssignmentType::class, $assignment);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $assignment->setAssignedBy($this->getUser());
            $assignment->setAssignedAt(new \DateTime());
            
            $entityManager->persist($assignment);
            $entityManager->flush();
            
            $this->addFlash('success', 'Test assigned successfully!');
            return $this->redirectToRoute('app_hr_dashboard');
        }
        
        return $this->render('hr/assign_test.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/assignment/{id}/toggle-status', name: 'app_hr_toggle_assignment_status', methods: ['POST'])]
    public function toggleAssignmentStatus(
        TestAssignment $assignment, 
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure user has HR role
        $this->denyAccessUnlessGranted('ROLE_HR');
        
        $assignment->setIsCompleted(!$assignment->getIsCompleted());
        $entityManager->flush();
        
        return $this->redirectToRoute('app_hr_dashboard');
    }
    
    #[Route('/assignment/{id}/delete', name: 'app_hr_delete_assignment', methods: ['POST'])]
    public function deleteAssignment(
        Request $request,
        TestAssignment $assignment, 
        EntityManagerInterface $entityManager
    ): Response {
        // Ensure user has HR role
        $this->denyAccessUnlessGranted('ROLE_HR');
        
        if ($this->isCsrfTokenValid('delete'.$assignment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($assignment);
            $entityManager->flush();
            $this->addFlash('success', 'Assignment deleted successfully');
        }
        
        return $this->redirectToRoute('app_hr_dashboard');
    }
}