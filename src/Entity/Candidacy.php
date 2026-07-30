<?php

namespace App\Entity;

use App\Repository\CandidacyRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\CandidacyStatus;

#[ORM\Entity(repositoryClass: CandidacyRepository::class)]
class Candidacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $motivation = null;

    #[ORM\Column(length: 255)]
    private ?string $cvFilePath = null;

    #[ORM\Column(length: 255)]
    private ?string $projectLinks = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'candidacies')]
    private ?User $freelance = null;

    #[ORM\ManyToOne]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'candidacies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CandidacyStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'candidacies')]
    private ?Mission $mission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $motivation): static
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getCvFilePath(): ?string
    {
        return $this->cvFilePath;
    }

    public function setCvFilePath(string $cvFilePath): static
    {
        $this->cvFilePath = $cvFilePath;

        return $this;
    }

    public function getProjectLinks(): ?string
    {
        return $this->projectLinks;
    }

    public function setProjectLinks(string $projectLinks): static
    {
        $this->projectLinks = $projectLinks;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFreelance(): ?User
    {
        return $this->freelance;
    }

    public function setFreelance(?User $freelance): static
    {
        $this->freelance = $freelance;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): static
    {
        $this->mission = $mission;

        return $this;
    }

    public function getStatus(): ?CandidacyStatus
    {
        return $this->status;
    }

    public function setStatus(?CandidacyStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}