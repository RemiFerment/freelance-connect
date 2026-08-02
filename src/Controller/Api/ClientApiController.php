<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/client')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ClientApiController extends AbstractController
{
    #[Route('', name: 'api_clients_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $clients = $userRepository->findClients();

        return $this->json(
            $clients,
            Response::HTTP_OK,
            [],
            ['groups' => 'client:read']
        );
    }


    #[Route('/{id}', name: 'api_client_show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository): JsonResponse
    {
        $client = $userRepository->find($id);

        if (!$client) {
            return $this->json([
                'message' => 'Client introuvable'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $client,
            Response::HTTP_OK,
            [],
            ['groups' => 'client:read']
        );
    }
}