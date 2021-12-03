<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users", name="app.admin.users")
     */
    public function users(): Response
    {
        return $this->render('dashboard/content/admin/admin_users.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
