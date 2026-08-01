<?php

namespace App\Controller;

use App\Entity\Candidacy;
use App\Interfaces\CandidacyManagerInterface;
use App\Interfaces\MissionManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    #[Route('/candidacy/accept/{id}', name: 'app_candidacy_accept')]
    public function accept(Candidacy $candidacy, MissionManagerInterface $missionManager): Response
    {
        try {
            $missionManager->acceptCandidacy($candidacy->getMission(), $candidacy, $this->getUser());
            $this->addFlash("success", "La candidature a bien été acceptée.");
            return $this->redirectToRoute('app_candidacy', ['id' => $candidacy->getId()], Response::HTTP_SEE_OTHER);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash("danger", $e->getMessage());
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        } catch (\LogicException $e) {
            $this->addFlash("danger", $e->getMessage());
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('/candidacy/refuse/{id}', name: 'app_candidacy_refuse')]
    public function refuse(Candidacy $candidacy, MissionManagerInterface $missionManager): Response
    {
        try {
            $missionManager->refuseCandidacy($candidacy->getMission(), $candidacy, $this->getUser());
            $this->addFlash("warning", "La candidature a bien été refusée.");
            return $this->redirectToRoute('app_candidacy', ['id' => $candidacy->getId()], Response::HTTP_SEE_OTHER);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash("danger", $e->getMessage());
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        } catch (\LogicException $e) {
            $this->addFlash("danger", $e->getMessage());
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/candidacy/file/{id}', name: 'app_candidacy_file')]
    public function getCvFileFromCandidacy(Candidacy $candidacy, CandidacyManagerInterface $candidacyManager): Response
    {
        try {
            $file = $candidacyManager->getCvFile($candidacy, $this->getUser());

            $filename = 'CV_candidature_' . $candidacy->getFreelance()->getFirstname() . "_" . $candidacy->getFreelance()->getLastname() . '.pdf';

            return $this->file($file, $filename, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        } catch (\InvalidArgumentException | FileNotFoundException $e) {
            $this->addFlash("danger", $e->getMessage());
            return $this->redirectToRoute('app_home');
        }
    }
}
