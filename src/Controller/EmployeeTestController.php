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
use App\Entity\UserTestResult;
use App\Repository\UserTestResultRepository;

#[Route('/employee')]
class EmployeeTestController extends AbstractController
{

    #[Route('/my-tests', name: 'app_employee_tests')]
    public function myTests(EntityManagerInterface $entityManager, UserTestResultRepository $testResultRepository): Response
    {
        /** @var \App\Entity\User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }
        $assignedTests = $currentUser->getTests();
        $rawIds = $testResultRepository->findPassedTestIdsByUser($currentUser->getId());
        $passedTestIds = array_column($rawIds, 'test_id'); // Just the list of IDs
        return $this->render('TestT/my_tests.html.twig', [
            'employee' => $currentUser,
            'assignedTests' => $assignedTests,
            'passedTestIds' => $passedTestIds,


        ]);
    }

    #[Route('/test/{id}/pass', name: 'app_employee_test_pass')]
   public function passTest(int $id, EntityManagerInterface $entityManager, Request $request): Response
{
    /** @var \App\Entity\User $currentUser */
    $currentUser = $this->getUser();

    if (!$currentUser) {
        return $this->redirectToRoute('app_login');
    }

    $test = $entityManager->getRepository(TestTechnique::class)->find($id);
    if (!$test) {
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Test non trouvé'
            ], 404);
        }

        $this->addFlash('error', 'Test non trouvé');
        return $this->redirectToRoute('app_employee_tests');
    }

    // Check if the user has access to this test
    if (!$currentUser->getTests()->contains($test)) {
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Vous n\'avez pas accès à ce test'
            ], 403);
        }

        throw new AccessDeniedException('You do not have access to this test');
    }

    $existingResult = $entityManager->getRepository(UserTestResult::class)
        ->findOneBy([
            'user' => $currentUser,
            'test' => $test,
        ]);

    // For AJAX checking if test was already taken
    if ($request->isXmlHttpRequest() && $request->query->get('check') === 'taken') {
        return $this->json([
            'alreadyTaken' => $existingResult !== null
        ]);
    }

    // Get the test result if already taken for displaying
    $testResult = null;
    if ($existingResult) {
        $testResult = [
            'score' => $existingResult->getScore(),
            'datePassed' => $existingResult->getDatePassed()->format('d/m/Y H:i'),
        ];
    }

    $questions = $test->getQuestions();

    // Make sure there are questions
    if (count($questions) === 0) {
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'status' => 'error',
                'message' => 'Ce test ne contient pas de questions'
            ]);
        }
        
        $this->addFlash('error', 'Ce test ne contient pas de questions');
        return $this->redirectToRoute('app_employee_tests');
    }

    $session = $request->getSession();
    $startTimeKey = 'test_start_time_' . $id;

    if (!$session->has($startTimeKey) && !$existingResult) {
        $session->set($startTimeKey, time());
    }

    $timeRemaining = 0;
    if (!$existingResult) {
        $startTime = $session->get($startTimeKey, time());
        $testDuration = $test->getDureeMinutes() * 60; // Convert to seconds
        $timeElapsed = time() - $startTime;
        $timeRemaining = max(0, $testDuration - $timeElapsed);
    }

    // Handle POST submissions
    if ($request->isMethod('POST') && !$existingResult) {
        // Use request->request for POST parameters instead of get()
        $answers = $request->request->get('answers', []);
        $isExpired = $request->request->get('expired', false);

        $totalScore = 0;
        $maxScore = 0;

        foreach ($questions as $question) {
            $difficulty = $question->getDifficulte();
            $maxScore += $difficulty;

            $questionId = $question->getId();
            $submittedAnswer = isset($answers[$questionId]) ? $answers[$questionId] : null;

            if ($question->getReponseCorrecte() === $submittedAnswer) {
                $totalScore += $difficulty;
            }
        }

        try {
            $testResult = new UserTestResult();
            $testResult->setUser($currentUser);
            $testResult->setTest($test);
            $testResult->setScore($totalScore);
            $testResult->setDatePassed(new \DateTime());

            $entityManager->persist($testResult);
            $entityManager->flush();

            $session->remove($startTimeKey);

            // Return JSON response for AJAX requests
            if ($request->isXmlHttpRequest()) {
                $message = $isExpired ? 'Le temps est écoulé. Votre test a été automatiquement soumis.' : 'Test terminé avec succès!';
                $status = $isExpired ? 'warning' : 'success';

                // Add redirectUrl to the response - THIS IS CRITICAL
                return $this->json([
                    'status' => $status,
                    'message' => $message,
                    'score' => $totalScore,
                    'maxScore' => $maxScore,
                    'testCompleted' => true,
                    'redirectUrl' => $this->generateUrl('app_employee_test_result', ['id' => $test->getId()])
                ]);
            }

            // Fallback for non-AJAX
            $this->addFlash('success', 'Test terminé avec succès!');
            return $this->redirectToRoute('app_employee_test_result', ['id' => $test->getId()]);
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Erreur lors de l\'enregistrement du résultat: ' . $e->getMessage()
                ], 500);
            }
            
            $this->addFlash('error', 'Erreur lors de l\'enregistrement du résultat');
            return $this->redirectToRoute('app_employee_tests');
        }
    }

    return $this->render('TestT/pass_test.html.twig', [
        'test' => $test,
        'questions' => $questions,
        'timeRemaining' => $timeRemaining,
        'testResult' => $testResult,
        'alreadyTaken' => $existingResult !== null
    ]);
}


    #[Route('/test/result/{id}', name: 'app_employee_test_result')]
    public function showResult(TestTechnique $test, EntityManagerInterface $em): Response
    {

        /** @var \App\Entity\User $currentUser */
        $user = $this->getUser();
        $testResult = $em->getRepository(UserTestResult::class)->findOneBy([
            'test' => $test,
            'user' => $user
        ]);

        if (!$testResult) {
            $this->addFlash('warning', 'Aucun résultat trouvé pour ce test.');
            return $this->redirectToRoute('app_employee_tests');
        }



        return $this->render('TestT/show_result.html.twig', [
            'test' => $test,
            'testResult' => $testResult,

        ]);
    }
}
