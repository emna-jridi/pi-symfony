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
            $message = 'Test non trouvé';
            if ($request->isXmlHttpRequest()) {
                return $this->json(['status' => 'error', 'message' => $message], 404);
            }
            $this->addFlash('error', $message);
            return $this->redirectToRoute('app_employee_tests');
        }
    
        if (!$currentUser->getTests()->contains($test)) {
            $message = 'Vous n\'avez pas accès à ce test';
            if ($request->isXmlHttpRequest()) {
                return $this->json(['status' => 'error', 'message' => $message], 403);
            }
            throw new AccessDeniedException($message);
        }
    
        $existingResult = $entityManager->getRepository(UserTestResult::class)->findOneBy([
            'user' => $currentUser,
            'test' => $test,
        ]);
    
        if ($existingResult) {
            $redirectUrl = $this->generateUrl('app_employee_test_result', ['id' => $test->getId()]);
            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'status' => 'info',
                    'message' => 'Vous avez déjà passé ce test',
                    'redirectUrl' => $redirectUrl
                ]);
            }
            return $this->redirectToRoute('app_employee_test_result', ['id' => $test->getId()]);
        }
    
        if ($request->isXmlHttpRequest() && $request->query->get('check') === 'taken') {
            return $this->json(['alreadyTaken' => $existingResult !== null]);
        }
    
        $questions = $test->getQuestions();
        if (count($questions) === 0) {
            $message = 'Ce test ne contient pas de questions';
            if ($request->isXmlHttpRequest()) {
                return $this->json(['status' => 'error', 'message' => $message]);
            }
            $this->addFlash('error', $message);
            return $this->redirectToRoute('app_employee_tests');
        }
    
        $session = $request->getSession();
        $startTimeKey = 'test_start_time_' . $id;
        if (!$session->has($startTimeKey)) {
            $session->set($startTimeKey, time());
        }
    
        $startTime = $session->get($startTimeKey);
        $testDuration = $test->getDureeMinutes() * 60;
        $timeElapsed = time() - $startTime;
        $timeRemaining = max(0, $testDuration - $timeElapsed);
    
        if ($request->isMethod('POST') || ($timeRemaining <= 0 && $request->isXmlHttpRequest() && $request->query->get('checkExpired') === 'true')) {
            $answers = $request->request->all()['answers'] ?? [];
            $isExpired = $timeRemaining <= 0 || $request->request->get('expired', false);
            
            // Nouveau calcul utilisant le score de chaque question
            $totalPoints = 0;
            $maxPoints = 0;
            $correctAnswers = 0;
            $totalQuestions = count($questions);
    
            foreach ($questions as $question) {
                $questionId = $question->getId();
                
                // Récupérer le score de la question
                $questionScore = $question->getScore();
                if ($questionScore === null) {
                    $questionScore = 1;
                }
                
                $maxPoints += $questionScore;
                
                if (isset($answers[$questionId])) {
                    // Récupérer la réponse correcte 
                    $correctAnswer = $question->getReponseCorrecte();
                    
                    // Récupérer la réponse soumise et la convertir en nombre
                    $submittedAnswerIndex = (int)$answers[$questionId];
                    
                    // Comparer directement les indices (tous deux commencent à 1 maintenant)
                    if ($submittedAnswerIndex == $correctAnswer) {
                        $correctAnswers++;
                        $totalPoints += $questionScore;
                    }
                }
            }
    
            // Calcul du pourcentage de réponses correctes (ratio de questions correctes)
            $correctPercentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;
            
            // Calcul du score total (somme des points obtenus)
            $finalScore = $totalPoints;
    
            try {
                $testResult = new UserTestResult();
                $testResult->setUser($currentUser);
                $testResult->setTest($test);
                $testResult->setScore($finalScore);
                $testResult->setDatePassed(new \DateTime());
    
                $entityManager->persist($testResult);
                $entityManager->flush();
    
                $session->remove($startTimeKey);
    
                if ($request->isXmlHttpRequest()) {
                    $message = $isExpired ? 'Le temps est écoulé. Votre test a été automatiquement soumis.' : 'Test terminé avec succès!';
                    $status = $isExpired ? 'warning' : 'success';
    
                    return $this->json([
                        'status' => $status,
                        'message' => $message,
                        'score' => $finalScore,
                        'maxScore' => $maxPoints,
                        'correctAnswers' => $correctAnswers,
                        'totalQuestions' => $totalQuestions,
                        'correctPercentage' => $correctPercentage,
                        'testCompleted' => true,
                        'redirectUrl' => $this->generateUrl('app_employee_test_result', ['id' => $test->getId()])
                    ]);
                }
    
                $this->addFlash('success', 'Test terminé avec succès!');
                return $this->redirectToRoute('app_employee_test_result', ['id' => $test->getId()]);
            } catch (\Exception $e) {
                $error = 'Erreur lors de l\'enregistrement du résultat: ' . $e->getMessage();
                
                if ($request->isXmlHttpRequest()) {
                    return $this->json([
                        'status' => 'error', 
                        'message' => $error
                    ], 500);
                }
                $this->addFlash('error', $error);
                return $this->redirectToRoute('app_employee_tests');
            }
        }
    
        if ($request->isXmlHttpRequest() && $request->query->get('checkExpired') === 'true') {
            return $this->json([
                'timeRemaining' => $timeRemaining,
                'isExpired' => $timeRemaining <= 0
            ]);
        }
    
        return $this->render('TestT/pass_test.html.twig', [
            'test' => $test,
            'questions' => $questions,
            'timeRemaining' => $timeRemaining,
            'startTime' => $startTime,
            'testDuration' => $testDuration,
            'alreadyTaken' => false
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