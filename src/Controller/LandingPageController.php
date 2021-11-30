<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    /**
     * @Route("/", name="app.landingpage")
     */
    public function index(): Response
    {
        return $this->render('landing_page/content/index.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }
}
