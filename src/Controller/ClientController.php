<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Enum\MissionStatusEnum;
use App\Form\MissionType;
use App\Interfaces\MissionManagerInterface;
use App\Repository\MissionRepository;
use App\Repository\MissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted("ROLE_CLIENT")]
final class ClientController extends AbstractController
{
    #[Route('/overview', name: 'app_client_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository, MissionStatusRepository $missionStatusRep): Response
    {
        $pendingStatus = $missionStatusRep->findOneByCode(MissionStatusEnum::PENDING->value);
        $completedStatus = $missionStatusRep->findOneByCode(MissionStatusEnum::COMPLETED->value);
        $inProgressStatus = $missionStatusRep->findOneByCode(MissionStatusEnum::IN_PROGRESS->value);
        $showableStatus = [$pendingStatus, $completedStatus, $inProgressStatus];
        return $this->render('client/index.html.twig', [
            'missions' => $missionRepository->findAllMissionsByStatus($this->getUser(), $showableStatus),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function create(Request $request, MissionManagerInterface $missionManager): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $missionManager->create($mission, $this->getUser());
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/mission/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('client/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/mission/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, MissionManagerInterface $missionManager): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $missionManager->edit($mission, $this->getUser());
            } catch (\InvalidArgumentException $e) {
                $this->addFlash("danger", $e->getMessage());
            }

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/mission/{id}/cancel', name: 'app_client_mission_cancel', methods: ['POST'])]
    public function cancel(Request $request, Mission $mission, MissionManagerInterface $missionManager): Response
    {
        if ($this->isCsrfTokenValid('cancel' . $mission->getId(), $request->getPayload()->getString('_token'))) {
            $missionManager->cancel($mission, $this->getUser());
        }
        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mission/{id}', name: 'app_client_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Mission $mission, MissionManagerInterface $missionManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mission->getId(), $request->getPayload()->getString('_token'))) {
            $missionManager->delete($mission);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
