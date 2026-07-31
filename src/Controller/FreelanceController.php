<?php

namespace App\Controller;

use App\Entity\Candidacy;
use App\Entity\Mission;
use App\Form\CandidacyType;
use App\Repository\MissionRepository;
use App\Repository\CandidacyRepository;
use App\Interfaces\CandidacyManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/freelance')]
#[IsGranted('ROLE_FREELANCE')]
final class FreelanceController extends AbstractController
{
    #[Route('/mission/{id}/apply', name: 'app_freelance_apply', methods: ['GET', 'POST'])]
    public function apply(Mission $mission, Request $request, CandidacyManagerInterface $candidacyManager): Response
    {
        $candidacy = new Candidacy();

        $form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $candidacyManager->apply($candidacy, $this->getUser(), $mission);
                $this->addFlash('success', 'Votre candidature a bien été envoyée.');
                return $this->redirectToRoute('app_freelance_dashboard', [], Response::HTTP_SEE_OTHER);
            } catch (\UnexpectedValueException $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('app_freelance_dashboard', [], Response::HTTP_SEE_OTHER);
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('app_freelance_apply', ['id' => $mission->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('freelance/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/candidacies', name: 'app_freelance_candidacies', methods: ['GET'])]
    public function candidacies(CandidacyRepository $candidacyRepository): Response
    {

        $candidacies = $candidacyRepository
            ->findByFreelance($this->getUser());

        return $this->render('freelance/candidacies.html.twig', [
            'candidacies' => $candidacies,
        ]);
    }

    #[Route('/missions', name: 'app_freelance_missions', methods: ['GET'])]
    public function missions(MissionRepository $missionRepository): Response
    {
        $missions = $missionRepository->findOpenMissions();

        return $this->render('freelance/missions.html.twig', [
            'missions' => $missions,
        ]);
    }

    #[Route('/mission/{id}', name: 'app_freelance_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('freelance/show.html.twig', [
            'mission' => $mission,
        ]);
    }
}
