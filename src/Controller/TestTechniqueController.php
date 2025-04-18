<?php
// src/Controller/TestTechniqueController.php
namespace App\Controller;

use App\Entity\TestTechnique;
use App\Entity\TestCandidat;
use App\Form\TestCandidatType;
use App\Repository\TestTechniqueRepository;
use App\Repository\QuestionTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tests')]
class TestTechniqueController extends AbstractController
{
    #[Route('/tt', name: 'app_test_index', methods: ['GET'])]
    public function index(TestTechniqueRepository $testRepository): Response
    {
        return $this->render('TestT/index.html.twig', [
            'tests' => $testRepository->findAll(),
        ]);
    }

    #[Route('/candidat/new/{id}', name: 'app_test_candidat_new', methods: ['GET', 'POST'])]
    public function newCandidat(Request $request, TestTechnique $test, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $testCandidat = new TestCandidat();
        $testCandidat->setTest($test);
        
        $form = $this->createForm(TestCandidatType::class, $testCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testCandidat->setDatePassage(new \DateTime());
            $entityManager->persist($testCandidat);
            $entityManager->flush();

            // Stocker l'ID du candidat en session
            $session->set('test_candidat_id', $testCandidat->getId());
            
            return $this->redirectToRoute('app_test_passer', ['id' => $test->getId()]);
        }

        return $this->render('TestT/candidat_new.html.twig', [
            'test' => $test,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/passer/{id}', name: 'app_test_passer', methods: ['GET', 'POST'])]
    public function passer(Request $request, TestTechnique $test, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'ID du candidat depuis la session
        $testCandidatId = $session->get('test_candidat_id');
        
        if (!$testCandidatId) {
            $this->addFlash('error', 'Vous devez d\'abord vous inscrire pour passer ce test.');
            return $this->redirectToRoute('app_test_candidat_new', ['id' => $test->getId()]);
        }
        
        $testCandidat = $entityManager->getRepository(TestCandidat::class)->find($testCandidatId);
        
        if (!$testCandidat || $testCandidat->getTest()->getId() !== $test->getId()) {
            $this->addFlash('error', 'Une erreur est survenue. Veuillez vous réinscrire.');
            return $this->redirectToRoute('app_test_candidat_new', ['id' => $test->getId()]);
        }
        
        // Vérifier si le test a déjà été soumis
        if ($testCandidat->getDateSoumission() !== null) {
            return $this->redirectToRoute('app_test_resultat', ['id' => $testCandidatId]);
        }

        // Récupérer toutes les questions du test et les mélanger
        $questions = $test->getQuestions()->toArray();
        shuffle($questions);
        
        if ($request->isMethod('POST')) {
            $reponses = [];
            $score = 0;
            
            // Traiter les réponses
            foreach ($questions as $question) {
                $questionId = $question->getId();
                $reponseCandidat = $request->request->get('question_' . $questionId);
                
                // Stocker la réponse du candidat
                if ($reponseCandidat !== null) {
                    $reponses[$questionId] = (int) $reponseCandidat;
                    
                    // Vérifier si la réponse est correcte
                    if ((int) $reponseCandidat === $question->getReponseCorrecte()) {
                        $score++;
                    }
                }
            }
            
            // Mettre à jour le candidat avec les résultats
            $testCandidat->setReponses($reponses);
            $testCandidat->setScore($score);
            $testCandidat->setDateSoumission(new \DateTime());
            
            $entityManager->flush();
            
            // Rediriger vers la page de résultats
            return $this->redirectToRoute('app_test_resultat', ['id' => $testCandidatId]);
        }

        return $this->render('TestT/passer.html.twig', [
            'test' => $test,
            'testCandidat' => $testCandidat,
            'questions' => $questions,
        ]);
    }

    #[Route('/resultat/{id}', name: 'app_test_resultat', methods: ['GET'])]
    public function resultat(TestCandidat $testCandidat): Response
    {
        // Vérifier si le test a bien été soumis
        if ($testCandidat->getDateSoumission() === null) {
            $this->addFlash('error', 'Ce test n\'a pas encore été soumis.');
            return $this->redirectToRoute('app_test_passer', ['id' => $testCandidat->getTest()->getId()]);
        }
        
        $test = $testCandidat->getTest();
        $scoreMax = count($test->getQuestions());
        $scorePercent = ($testCandidat->getScore() / $scoreMax) * 100;
        
        return $this->render('TestT/resultat.html.twig', [
            'testCandidat' => $testCandidat,
            'test' => $test,
            'scoreMax' => $scoreMax,
            'scorePercent' => $scorePercent,
        ]);
    }
}