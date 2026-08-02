<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Attribute as MongoDB;

#[MongoDB\Document(collection: 'conversations')]
class Conversation
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'collection')]
    private array $participantIds = [];

    #[MongoDB\Field(type: 'string')]
    private ?string $lastMessageText = null;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $updatedAt;

    #[MongoDB\Field(type: 'hash')]
    private array $unreadCount = [];

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getParticipantIds(): array
    {
        return $this->participantIds;
    }

    public function setParticipantIds(array $participantIds): self
    {
        $this->participantIds = $participantIds;
        return $this;
    }

    public function getLastMessageText(): ?string
    {
        return $this->lastMessageText;
    }

    public function setLastMessageText(?string $lastMessageText): self
    {
        $this->lastMessageText = $lastMessageText;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUnreadCount(): array
    {
        return $this->unreadCount;
    }

    public function setUnreadCount(array $unreadCount): self
    {
        $this->unreadCount = $unreadCount;
        return $this;
    }
}
