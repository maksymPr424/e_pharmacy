<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultAdminController extends AbstractController
{
    #[Route('/', name: 'app_default_admin')]
    public function index(): Response
    {
        // $this->redirectToRoute('app_login');
        return $this->render('admin_base.html.twig');
    }
}
