<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TestTechnique;
use App\Form\AssignTestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tests')]
class AssignTestController extends AbstractController
{
    #[Route('/assign', name: 'app_admin_test_assign', methods: ['GET', 'POST'])]
    public function assignTest(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employees = $entityManager->getRepository(User::class)->findByRole('Employe');

        $tests = $entityManager->getRepository(TestTechnique::class)->findAll();

        $form = $this->createForm(AssignTestType::class, null, [
            'employees' => $employees,
            'tests' => $tests,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $employee = $data['employee'];
            $assignedTests = $data['tests'];

            foreach ($assignedTests as $test) {
                $employee->addTest($test); 
            }

            $entityManager->flush();

            $this->addFlash('success', 'Tests assignés avec succès !');

            return $this->redirectToRoute('app_admin_test_index');
        }

        return $this->render('TestT/admin/assign_test.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/assigned', name: 'app_admin_test_assigned', methods: ['GET'])]
    public function viewAssignedTests(EntityManagerInterface $entityManager): Response
    {
        $allEmployees = $entityManager->getRepository(User::class)->findByRole('Employe');
    
        $employeesWithTests = array_filter($allEmployees, function ($employee) {
            return count($employee->getTests()) > 0;
        });
    
        return $this->render('TestT/admin/assigned_tests.html.twig', [
            'employees' => $employeesWithTests,
        ]);
    }

}
