<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TetstController extends AbstractController
{
    #[Route('/tetst', name: 'app_tetst')]
    public function index(): Response
    {
        return $this->render('tetst/index.html.twig', [
            'controller_name' => 'TetstController',
        ]);
    }
}
