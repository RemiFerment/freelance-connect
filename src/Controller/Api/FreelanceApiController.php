<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/freelance')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class FreelanceApiController extends AbstractController
{
    #[Route('', name: 'api_freelances_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $freelances = $userRepository->findFreelances();

        return $this->json(
            $freelances,
            Response::HTTP_OK,
            [],
            ['groups' => 'freelance:read']
        );
    }


    #[Route('/{id}', name: 'api_freelance_show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository): JsonResponse
    {
        $freelance = $userRepository->findFreelanceById($id);

        if (!$freelance) {
            return $this->json([
                'message' => 'Freelance introuvable'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $freelance,
            Response::HTTP_OK,
            [],
            ['groups' => 'freelance:read']
        );
    }
}