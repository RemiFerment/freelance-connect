<?php

namespace App\Controller;

use App\Entity\Candidacy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CandidacyController extends AbstractController
{
    #[Route('/candidacy/{id}', name: 'app_candidacy')]
    #[IsGranted('ROLE_CLIENT')]
    public function show(Candidacy $candidacy): Response
    {
        if ($this->getUser() !== $candidacy->getMission()->getClient()) {
            $this->addFlash('danger', "Vous n'avez pas la permission d'accéder à cette ressource (403)");
            $this->redirectToRoute('app_home');
        }
        return $this->render('candidacy/show.html.twig', [
            'candidacy' => $candidacy,
        ]);
    }
}
