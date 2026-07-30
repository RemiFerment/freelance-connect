<?php

namespace App\Services;

use App\Entity\Mission;
use App\Entity\MissionStatus;
use App\Entity\User;
use App\Enum\MissionStatusEnum;
use App\Interfaces\MissionManagerInterface;
use App\Repository\MissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

final class MissionManager implements MissionManagerInterface
{
    public function __construct(private EntityManagerInterface $em, private MissionStatusRepository $missionStatusRepository) {}

    public function create(Mission $mission, User $currentUser): void
    {
        $mission->setClient($currentUser);
        $mission->setStatus($this->missionStatusRepository->findOneByCode(MissionStatusEnum::PENDING->value));
        if ($mission->getStatus() == null) {
            throw new \InvalidArgumentException("Un problème interne est survenue, merci de réessayer");
        }
        $this->em->persist($mission);
        $this->em->flush();
    }

    public function edit(Mission $mission, User $currentClient): void
    {
        if ($currentClient !== $mission->getClient()) {
            throw new \InvalidArgumentException("Cet utilisateur n'a pas les permissions nécessaire pour réaliser cette action.");
        }
        $mission->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($mission);
        $this->em->flush();
    }

    public function cancel(Mission $mission, User $currentClient): void {}

    public function acceptCandidacy(Mission $mission, User $client, User $freelance): void {}

    public function refuseCandidacy(Mission $mission, User $client, User $freelance): void {}

    public function delete(Mission $mission, User $currentUser): void {}
}
