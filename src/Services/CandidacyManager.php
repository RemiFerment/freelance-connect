<?php

namespace App\Services;

use App\Entity\Candidacy;
use App\Entity\User;
use App\Entity\Mission;
use App\Interfaces\CandidacyManagerInterface;
use App\Repository\CandidacyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\CandidacyStatusEnum;
use App\Repository\CandidacyStatusRepository;
use App\Enum\MissionStatusEnum;
use UnexpectedValueException;

class CandidacyManager implements CandidacyManagerInterface
{
    public function __construct(private EntityManagerInterface $em, private CandidacyRepository $candidacyRepository, private CandidacyStatusRepository $candidacyStatusRepository){ }

    public function apply(Candidacy $candidacy, User $freelance, Mission $mission): void {

        $existingCandidacy = $this->candidacyRepository
            ->findByFreelanceAndMission($freelance, $mission);

        if ($existingCandidacy !== null) {
            throw new UnexpectedValueException("Vous avez déjà envoyé votre candidature pour cette mission.");
        }

        if ($mission->getStatus()->getCode() !== MissionStatusEnum::PENDING->value) {
            throw new UnexpectedValueException("Cette mission ne reçoit plus de candidatures.");
        }

        $candidacy->setFreelance($freelance);
        $candidacy->setMission($mission);
        $candidacy->setClient($mission->getClient());
        $candidacy->setCreatedAt(new \DateTimeImmutable());

        $status = $this->candidacyStatusRepository->findOneByCode(CandidacyStatusEnum::PENDING->value);

        if ($status === null) {
            throw new \InvalidArgumentException("Un problème interne est survenu, merci de réessayer");
        }

        $candidacy->setStatus($status);
       
        $this->em->persist($candidacy);
        $this->em->flush();
    }
}