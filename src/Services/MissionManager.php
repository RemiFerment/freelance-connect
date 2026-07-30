<?php

namespace App\Services;

use App\Entity\Candidacy;
use App\Entity\Mission;
use App\Entity\User;
use App\Enum\MissionStatusEnum;
use App\Interfaces\MissionManagerInterface;
use App\Interfaces\NotificationManagerInterface;
use App\Repository\MissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

final class MissionManager implements MissionManagerInterface
{
    public function __construct(private EntityManagerInterface $em, private MissionStatusRepository $missionStatusRepository, private NotificationManagerInterface $notification_manager) {}

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
        $pendingStatus = $this->missionStatusRepository->findOneByCode(MissionStatusEnum::PENDING->value);
        if (!$mission->getStatus() !== $pendingStatus) {
            throw new \InvalidArgumentException("Vous ne pouvez pas modifier cette mission, merci de revoir nos conditions d'utilisations.");
        }
        $mission->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($mission);
        $this->em->flush();
    }

    public function cancel(Mission $mission, User $currentClient): void
    {
        if ($currentClient !== $mission->getClient()) {
            throw new \InvalidArgumentException("Cet utilisateur n'a pas les permissions nécessaire pour réaliser cette action.");
        }
        $allCandidacies = $mission->getCandidacies();
        if (count($allCandidacies) > 0) {
            $cancelMessage = "La mission " . $mission->getTitle() . " a été annulée.";
            foreach ($allCandidacies as $candidacy) {
                $this->notification_manager->send($currentClient, $candidacy->getFreelance(), $cancelMessage);
            }
        }
        $canceledStatus = $this->missionStatusRepository->findOneByCode(MissionStatusEnum::CANCELED->value);
        $mission->setStatus($canceledStatus);
        $this->em->persist($mission);
        $this->em->flush();
    }

    public function setMissionCompleted(Mission $mission): void
    {
        $mission->setStatus($this->missionStatusRepository->findOneByCode(MissionStatusEnum::COMPLETED->value));
        $this->em->persist($mission);
        $this->em->flush();
    }

    public function acceptCandidacy(Mission $mission, Candidacy $candidacy): void {}

    public function refuseCandidacy(Mission $mission, Candidacy $candidacy): void {}

    public function delete(Mission $mission): void
    {
        $this->em->remove($mission);
        $this->em->flush();
    }
}
