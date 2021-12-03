<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app")
 */
class AppController extends AbstractController
{
    /**
     * @Route("/", name="app.home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('dashboard/content/main.html.twig');
    }
}