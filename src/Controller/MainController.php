<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/app", name="app.home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('dashboard/content/main.html.twig');
    }
}