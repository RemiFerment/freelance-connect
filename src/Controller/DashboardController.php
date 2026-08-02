<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('client/dashboard', name: 'app_client_dashboard')]
    #[IsGranted('ROLE_CLIENT')]
    public function clientDashboard(): Response
    {
        return $this->render('client/dashboard.html.twig',);
    }

    #[Route('freelance/dashboard', name: 'app_freelance_dashboard')]
    #[IsGranted('ROLE_FREELANCE')]
    public function freelanceDashboard(): Response
    {
        return $this->render('freelance/dashboard.html.twig',);
    }

    #[Route('admin/dashboard', name: 'app_admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig',);
    }
}

