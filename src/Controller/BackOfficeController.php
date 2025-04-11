<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BackOfficeUserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ResponsableRH')]
final class BackOfficeController extends AbstractController
{
    #[Route('/back', name: 'app_back_office')]
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

    #[Route('/back/settings', name: 'app_back_settings')]
    public function settings(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(BackOfficeUserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_back_settings');
        }

        return $this->render('back_office/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
