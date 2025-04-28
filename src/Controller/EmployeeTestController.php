<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TestTechnique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\QuestionTechnique;


#[Route('/employee')]
class EmployeeTestController extends AbstractController
{
    /**
     * Show all tests assigned to the currently logged-in employee.
     */
    #[Route('/my-tests', name: 'app_employee_tests')]
    public function myTests(EntityManagerInterface $entityManager): Response
    {
        // Get the current logged-in user
        $currentUser = $this->getUser();
        
        // Check if user is logged in
        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }

        
        
        // Get the tests assigned to this employee
        $assignedTests = $currentUser->getTests();

        // Render the view and pass the assigned tests
        return $this->render('TestT/my_tests.html.twig', [
            'employee' => $currentUser,
            'assignedTests' => $assignedTests,
        ]);
    }
    #[Route('/test/{id}/pass', name: 'app_employee_test_pass')]
    public function passTest(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }

        // Find the test by ID
        $test = $entityManager->getRepository(TestTechnique::class)->find($id);

        if (!$test) {
            throw $this->createNotFoundException('Test not found');
        }

        $userTests = $currentUser->getTests();
        if (!$userTests->contains($test)) {
            throw new AccessDeniedException('You do not have access to this test');
        }

        $questions = $test->getQuestions(); 

       
        if ($request->isMethod('POST')) {
            // Process the answers here
            $answers = $request->get('answers');
            
            foreach ($questions as $question) {
                $answer = $answers[$question->getId()] ?? null;

                
            }

            $test->setCompleted(true);
            $entityManager->flush();

        
            $this->addFlash('success', 'Test completed successfully!');

            return $this->redirectToRoute('app_employee_tests');
        }

   
        return $this->render('TestT/pass_test.html.twig', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }
}
