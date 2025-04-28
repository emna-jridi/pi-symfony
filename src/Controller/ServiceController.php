<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //ajouter service
    #[Route('/addS', name: 'add_s', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($service);
            $this->entityManager->flush();

            return $this->redirectToRoute('list_s');
        }

        return $this->render('back_office/Servicee/addService.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // afficher liste des services
    #[Route('/listS', name: 'list_s')]
    public function list(): Response
    {
        $services = $this->entityManager->getRepository(Service::class)->findAll();

        return $this->render('front_office/Servicee/listServices.html.twig', [
            'services' => $services,
        ]);
    }

    // afficher détails du service
    #[Route('/Service/{idService}', name: 'service_show')]
    public function show(int $idService, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->find($idService);

        if (!$service) {
            throw new NotFoundHttpException('Service non trouvé');
        }

        return $this->render('front_office/Servicee/showService.html.twig', [
            'service' => $service,
            'contratServices' => $service->getContratServices(),
        ]);
    }

    //modifier service
    #[Route('/Service/{idService}/edit', name: 'service_edit')]
    public function edit(int $idService, Request $request, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->find($idService);

        if (!$service) {
            throw $this->createNotFoundException('Service non trouvé');
        }

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('list_s', ['idService' => $service->getIdService()]);
        }

        return $this->render('back_office/Servicee/modifService.html.twig', [
            'form' => $form->createView(),
            'service' => $service,
        ]);
    }





    //supprimer service

    #[Route('/servicee/{idService}/delete', name: 'service_delete')]
    public function delete(int $idService, ServiceRepository $serviceRepository): RedirectResponse
    {
        $service = $serviceRepository->find($idService);

        if (!$service) {
            throw $this->createNotFoundException('Service non trouvé.');
        }

        foreach ($service->getContratServices() as $contratService) {
            $this->entityManager->remove($contratService);
        }
        $this->entityManager->flush();
        $this->entityManager->remove($service);
        $this->entityManager->flush();
        


        return $this->redirectToRoute('list_s'); 
    }















}
