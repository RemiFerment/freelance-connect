<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\MessagingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/messaging')]
final class MessagingController extends AbstractController
{
    public function __construct(
        private MessagingService $messagingService,
        private UserRepository $userRepository,
    ) {}

    #[Route('', name: 'app_messaging')]
    public function index(): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $conversations = $this->messagingService->getConversationsForUser($currentUser);

        return $this->render('messaging/index.html.twig', [
            'conversations' => $conversations,
            'activeConversation' => null,
            'messages' => [],
            'participantsMap' => $this->buildParticipantsMap($conversations),
            'currentUserId' => (string) $currentUser->getId(),
        ]);
    }

    #[Route('/open/{userId}', name: 'app_messaging_open')]
    public function open(int $userId): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $otherUser = $this->userRepository->find($userId);

        if (!$otherUser || $otherUser->getId() === $currentUser->getId()) {
            throw $this->createNotFoundException();
        }

        $conversation = $this->messagingService->findOrCreateConversation($currentUser, $otherUser);

        if (!$conversation) {
            $this->addFlash('error', 'Vous ne pouvez pas envoyer de message à cet utilisateur. Une mission en cours ou terminée est requise.');
            return $this->redirectToRoute('app_messaging');
        }

        return $this->redirectToRoute('app_messaging_show', ['id' => $conversation->getId()]);
    }

    #[Route('/{id}', name: 'app_messaging_show')]
    public function show(string $id): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $conversation = $this->messagingService->findConversation($id);

        if (!$conversation || !in_array((string) $currentUser->getId(), $conversation->getParticipantIds(), true)) {
            throw $this->createNotFoundException();
        }

        $this->messagingService->markAsRead($conversation, $currentUser);
        $messages = $this->messagingService->getMessages($conversation);
        $conversations = $this->messagingService->getConversationsForUser($currentUser);

        $allForMap = $conversations;
        if (!in_array($conversation, $allForMap, true)) {
            $allForMap[] = $conversation;
        }

        return $this->render('messaging/index.html.twig', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
            'messages' => $messages,
            'participantsMap' => $this->buildParticipantsMap($allForMap),
            'currentUserId' => (string) $currentUser->getId(),
        ]);
    }

    #[Route('/{id}/send', name: 'app_messaging_send', methods: ['POST'])]
    public function send(string $id, Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $conversation = $this->messagingService->findConversation($id);

        if (!$conversation || !in_array((string) $currentUser->getId(), $conversation->getParticipantIds(), true)) {
            throw $this->createNotFoundException();
        }

        if (!$this->isCsrfTokenValid('send_message_' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $content = trim((string) $request->request->get('content', ''));
        if ($content !== '') {
            $this->messagingService->sendMessage($conversation, $currentUser, $content);
        }

        return $this->redirectToRoute('app_messaging_show', ['id' => $id]);
    }

    private function buildParticipantsMap(array $conversations): array
    {
        $userIds = [];
        foreach ($conversations as $conversation) {
            foreach ($conversation->getParticipantIds() as $participantId) {
                $userIds[(int) $participantId] = true;
            }
        }

        if (empty($userIds)) {
            return [];
        }

        $users = $this->userRepository->findBy(['id' => array_keys($userIds)]);
        $map = [];
        foreach ($users as $user) {
            $map[(string) $user->getId()] = $user;
        }

        return $map;
    }
}
