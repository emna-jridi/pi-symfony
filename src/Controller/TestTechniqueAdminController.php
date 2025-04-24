<?php
// src/Controller/TestTechniqueAdminController.php
namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

use App\Entity\QuestionTechnique;
use App\Entity\TestTechnique;
use App\Entity\TestCandidat;
use App\Form\QuestionTechniqueType;
use App\Form\TestTechniqueType;
use App\Repository\QuestionTechniqueRepository;
use App\Repository\TestTechniqueRepository;
use App\Repository\TestCandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/tests')]

class TestTechniqueAdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_test_index', methods: ['GET'])]
    public function index(TestTechniqueRepository $testRepository): Response
    {
        return $this->render('TestT/admin/index.html.twig', [
            'tests' => $testRepository->findWithQuestions(),
        ]);
    }

    #[Route('/new', name: 'app_admin_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $test = new TestTechnique();
        $form = $this->createForm(TestTechniqueType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($test);
            $entityManager->flush();

            $this->addFlash('success', 'Test créé avec succès.');
            return $this->redirectToRoute('app_admin_test_index');
        }

        return $this->render('TestT/admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_test_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TestTechnique $test, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TestTechniqueType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Test modifié avec succès.');
            return $this->redirectToRoute('app_admin_test_index');
        }

        return $this->render('TestT/admin/edit.html.twig', [
            'test' => $test,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_test_delete', methods: ['GET'])]
    public function delete(Request $request, TestTechnique $test, EntityManagerInterface $entityManager): Response
    {
        // // Vérifier si le test a déjà été passé par des candidats
        // if (count($test->getTestCandidats()) > 0) {
        //     $this->addFlash('error', 'Ce test ne peut pas être supprimé car il a déjà été passé par des candidats.');
        //     return $this->redirectToRoute('app_admin_test_index');
        // }

        $entityManager->remove($test);
        $entityManager->flush();

        $this->addFlash('success', 'Test supprimé avec succès.');
        return $this->redirectToRoute('app_admin_test_index');
    }

    #[Route('/question/new', name: 'app_admin_question_new', methods: ['GET', 'POST'])]
    public function newQuestion(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new QuestionTechnique();
        // Initialiser avec 4 options vides
        $question->setOptions(['', '', '', '']);
        
        $form = $this->createForm(QuestionTechniqueType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question créée avec succès.');
            return $this->redirectToRoute('app_admin_question_index');
        }

        return $this->render('TestT/admin/question_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/questions', name: 'app_admin_question_index', methods: ['GET'])]
    public function questionIndex(
        QuestionTechniqueRepository $questionRepository, 
        PaginatorInterface $paginator, 
        Request $request
    ): Response {
        $query = $questionRepository->createQueryBuilder('q')->orderBy('q.id', 'DESC');
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Numéro de page
            10 // Nombre de questions par page
        );
    
        return $this->render('TestT/admin/question_index.html.twig', [
            'questions' => $pagination,
        ]);
    }
    
    #[Route('/question/edit/{id}', name: 'app_admin_question_edit', methods: ['GET', 'POST'])]
    public function editQuestion(Request $request, QuestionTechnique $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionTechniqueType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Question modifiée avec succès.');
            return $this->redirectToRoute('app_admin_question_index');
        }

        return $this->render('TestT/admin/question_edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/question/delete/{id}', name: 'app_admin_question_delete', methods: ['GET'])]
    public function deleteQuestion(Request $request, QuestionTechnique $question, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la question est utilisée dans des tests
        $tests = $entityManager->getRepository(TestTechnique::class)->findByQuestion($question);
        
        if (count($tests) > 0) {
            $this->addFlash('error', 'Cette question ne peut pas être supprimée car elle est utilisée dans des tests existants.');
            return $this->redirectToRoute('app_admin_question_index');
        }

        $entityManager->remove($question);
        $entityManager->flush();

        $this->addFlash('success', 'Question supprimée avec succès.');
        return $this->redirectToRoute('app_admin_question_index');
    }

    #[Route('/resultats/{id}', name: 'app_admin_test_resultats', methods: ['GET'])]
    public function resultats(TestTechnique $test, TestCandidatRepository $testCandidatRepository): Response
    {
        $testCandidats = $testCandidatRepository->findByTest($test);
        $avgScore = $testCandidatRepository->getAverageScore($test);
        
        // Calculer le score maximum
        $scoreMax = 0;
        if (!empty($testCandidats)) {
            foreach ($testCandidats as $candidat) {
                $score = ($candidat->getScore() / count($test->getQuestions())) * 100;
                if ($score > $scoreMax) {
                    $scoreMax = $score;
                }
            }
        }

        return $this->render('TestT/admin/resultats.html.twig', [
            'test' => $test,
            'testCandidats' => $testCandidats,
            'avgScore' => $avgScore ? ($avgScore / count($test->getQuestions())) * 100 : 0,
            'maxScore' => $scoreMax,
        ]);
    }

    #[Route('/candidat/{id}', name: 'app_admin_test_candidat_detail', methods: ['GET'])]
    public function candidatDetail(TestCandidat $testCandidat): Response
    {
        $test = $testCandidat->getTest();
        $questions = $test->getQuestions()->toArray();
        $reponses = $testCandidat->getReponses();
        
        return $this->render('TestT/admin/candidat_detail.html.twig', [
            'testCandidat' => $testCandidat,
            'test' => $test,
            'questions' => $questions,
            'reponses' => $reponses,
        ]);
        
    }
    #[Route('/employee/{id}/dashboard', name: 'employee_dashboard')]
    public function employeeDashboard($id, EntityManagerInterface $entityManager): Response
    {
        $employee = $entityManager->getRepository(User::class)->find($id);
    
        if (!$employee) {
            throw $this->createNotFoundException('Employee not found.');
        }
    
        $assignedTests = $employee->getTests();
    
        return $this->render('TestT/dashboardT.html.twig', [
            'employee' => $employee,
            'assignedTests' => $assignedTests,
        ]);
    }
    
    
}