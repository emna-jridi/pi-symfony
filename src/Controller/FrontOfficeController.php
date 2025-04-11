<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_Employe')]
final class FrontOfficeController extends AbstractController
{
    #[Route('/front', name: 'app_front_office')]
    public function index(): Response
    {
        return $this->render('front_office/index.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }

    #[Route('/front/settings', name: 'app_front_settings')]
    public function settings(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_front_settings');
        }

        return $this->render('front_office/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
