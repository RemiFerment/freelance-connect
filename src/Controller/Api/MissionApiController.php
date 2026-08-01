<?php

namespace App\Controller\Api;

use App\Entity\Mission;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/missions')]
final class MissionApiController extends AbstractController
{
    #[Route('/recent', name: 'api_missions_recent', methods: ['GET'])]
    public function recent(MissionRepository $missionRepository): JsonResponse
    {
        $missions = $missionRepository->findRecentOpenMissions();

        return $this->json(
            $missions,
            Response::HTTP_OK,
            [],
            ['groups' => 'mission:read']
        );
    }

    #[Route('', name: 'api_missions_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): JsonResponse
    {
        $missions = $missionRepository->findOpenMissions();

        return $this->json(
            $missions,
            Response::HTTP_OK,
            [],
            ['groups' => 'mission:read']
        );
    }

    #[Route('/{id}/applications', name: 'api_mission_applications', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function applications(MissionRepository $missionRepository, int $id): JsonResponse
    {
        $mission = $missionRepository->find($id);

        if (!$mission) {
            return $this->json([
                'message' => 'Mission introuvable'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'missionId' => $mission->getId(),
            'applicationsCount' => $mission->getCandidacies()->count(),
        ]);

    }
}