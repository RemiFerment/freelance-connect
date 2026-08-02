<?php

namespace App\Services;

use App\Document\Conversation;
use App\Document\Message;
use App\Entity\User;
use App\Repository\MissionRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class MessagingService
{
    public function __construct(
        private DocumentManager $dm,
        private MissionRepository $missionRepository,
    ) {}

    public function canMessage(User $user1, User $user2): bool
    {
        return $this->missionRepository->findMissionBetweenUsers($user1, $user2) !== null;
    }

    public function getConversationsForUser(User $user): array
    {
        return $this->dm->createQueryBuilder(Conversation::class)
            ->field('participantIds')->in([(string) $user->getId()])
            ->sort('updatedAt', 'desc')
            ->getQuery()
            ->execute()
            ->toArray();
    }

    public function findConversation(string $id): ?Conversation
    {
        return $this->dm->find(Conversation::class, $id);
    }

    public function findConversationBetween(string $userId1, string $userId2): ?Conversation
    {
        return $this->dm->createQueryBuilder(Conversation::class)
            ->field('participantIds')->all([$userId1, $userId2])
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findOrCreateConversation(User $user1, User $user2): ?Conversation
    {
        if (!$this->canMessage($user1, $user2)) {
            return null;
        }

        $existing = $this->findConversationBetween(
            (string)$user1->getId(),
            (string)$user2->getId()
        );

        if ($existing) {
            return $existing;
        }

        $conversation = new Conversation();
        $conversation->setParticipantIds([
            (string) $user1->getId(),
            (string) $user2->getId(),
        ]);

        $this->dm->persist($conversation);
        $this->dm->flush();

        return $conversation;
    }

    public function sendMessage(Conversation $conversation, User $sender, string $content): Message
    {
        $message = new Message();
        $message->setConversationId($conversation->getId());
        $message->setSenderId((string) $sender->getId());
        $message->setContent($content);

        $conversation->setLastMessageText($content);
        $conversation->setUpdatedAt(new \DateTime());

        $unreadCount = $conversation->getUnreadCount();
        foreach ($conversation->getParticipantIds() as $participantId) {
            if ($participantId !== (string) $sender->getId()) {
                $unreadCount[$participantId] = ($unreadCount[$participantId] ?? 0) + 1;
            }
        }
        $conversation->setUnreadCount($unreadCount);

        $this->dm->persist($message);
        $this->dm->flush();

        return $message;
    }

    public function getMessages(Conversation $conversation, int $limit = 50): array
    {
        return $this->dm->createQueryBuilder(Message::class)
            ->field('conversationId')->equals($conversation->getId())
            ->sort('createdAt', 'asc')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ->toArray();
    }

    public function markAsRead(Conversation $conversation, User $user): void
    {
        $unreadCount = $conversation->getUnreadCount();
        $userId = (string)$user->getId();

        if (!empty($unreadCount[$userId])) {
            $unreadCount[$userId] = 0;
            $conversation->setUnreadCount($unreadCount);
            $this->dm->flush();
        }
    }
}