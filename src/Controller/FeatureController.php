<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FeatureController extends AbstractController
{
    #[Route('/feature/{name}', name: 'app_feature_pending')]
    public function pending(string $name): Response
    {
        return $this->render('feature/under_development.html.twig', [
            'feature' => $name,
        ]);
    }
}