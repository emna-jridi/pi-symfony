<?php

namespace App\Controller;

use App\Entity\Reunion;
use App\Form\ReunionType;
use App\Repository\ReunionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reunion')]
final class ReunionController extends AbstractController
{
    #[Route(name: 'app_reunion_index', methods: ['GET'])]
    public function index(ReunionRepository $reunionRepository): Response
    {
        return $this->render('reunion/index.html.twig', [
            'reunions' => $reunionRepository->findAll(),
        ]);
    }

    #[Route('/calendar', name: 'app_reunion_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        // Simply render the template; data will be fetched via API
        return $this->render('reunion/calendar.html.twig');
    }

    #[Route('/events', name: 'app_reunion_events', methods: ['GET'])]
    public function events(ReunionRepository $reunionRepository): JsonResponse
    {
        $reunions = $reunionRepository->findAll();
        $events = [];

        foreach ($reunions as $reunion) {
            // Skip events with missing required fields (e.g., date)
            if (!$reunion->getDate()) {
                continue;
            }

            $events[] = [
                'id' => $reunion->getId(),
                'title' => $reunion->getTitre() ?? 'Untitled', // Map titre to title
                'start' => $reunion->getDate()->format('Y-m-d'), // Map date to start
                'description' => $reunion->getDescription() ?? '',
                'type' => $reunion->getType() ?? '',
                'editUrl' => $this->generateUrl('app_reunion_edit', ['id' => $reunion->getId()]),
                'deleteUrl' => $this->generateUrl('app_reunion_delete', ['id' => $reunion->getId()]),
                'deleteToken' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $reunion->getId())->getValue(),
                'googleCalendarLink' => $this->generateGoogleCalendarLink($reunion),
                'allDay' => true,
            ];
        }

        return new JsonResponse($events);
    }

    #[Route('/new', name: 'app_reunion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reunion = new Reunion();
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reunion);
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reunion/new.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_show', methods: ['GET'])]
    public function show(Reunion $reunion): Response
    {
        $googleCalendarLink = $this->generateGoogleCalendarLink($reunion);

        return $this->render('reunion/show.html.twig', [
            'reunion' => $reunion,
            'googleCalendarLink' => $googleCalendarLink,
        ]);
    }

    /**
     * Generate a Google Calendar event link for the reunion.
     */
    private function generateGoogleCalendarLink(Reunion $reunion): string
    {
        $baseUrl = 'https://www.google.com/calendar/render';

        $title = urlencode($reunion->getTitre() ?? 'Réunion');
        $description = urlencode($reunion->getDescription() ?? 'Réunion organisée via l\'application.');
        $location = ''; // No location field in the entity

        $startDate = $reunion->getDate();
        if (!$startDate) {
            $startDate = new \DateTime();
        }

        $duration = 60; // Default duration in minutes
        $endDate = (clone $startDate)->modify("+{$duration} minutes");

        $startFormatted = $startDate->format('Ymd\THis\Z');
        $endFormatted = $endDate->format('Ymd\THis\Z');

        $queryParams = [
            'action' => 'TEMPLATE',
            'text' => $title,
            'dates' => "{$startFormatted}/{$endFormatted}",
            'details' => $description,
            'location' => $location,
        ];

        $queryString = http_build_query($queryParams);
        return "{$baseUrl}?{$queryString}";
    }

    #[Route('/{id}/edit', name: 'app_reunion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reunion/edit.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reunion_delete', methods: ['POST'])]
    public function delete(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reunion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reunion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reunion_index', [], Response::HTTP_SEE_OTHER);
    }
}