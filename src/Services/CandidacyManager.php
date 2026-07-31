<?php

namespace App\Services;

use App\Entity\Candidacy;
use App\Entity\CandidacyStatus;
use App\Entity\User;
use App\Entity\Mission;
use App\Interfaces\CandidacyManagerInterface;
use App\Repository\CandidacyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\CandidacyStatusEnum;
use App\Repository\CandidacyStatusRepository;
use App\Enum\MissionStatusEnum;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use UnexpectedValueException;

class CandidacyManager implements CandidacyManagerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CandidacyRepository $candidacyRepository,
        private CandidacyStatusRepository $candidacyStatusRepository,
        private KernelInterface $kernel
    ) {}

    public function apply(Candidacy $candidacy, User $freelance, Mission $mission, UploadedFile $file): void
    {
        $fileSystem = new Filesystem();

        $existingCandidacy = $this->candidacyRepository
            ->findByFreelanceAndMission($freelance, $mission);
        if ($existingCandidacy !== null) {
            throw new UnexpectedValueException("Vous avez déjà envoyé votre candidature pour cette mission.");
        }

        if ($mission->getStatus()->getCode() !== MissionStatusEnum::PENDING->value) {
            throw new UnexpectedValueException("Cette mission ne reçoit plus de candidatures.");
        }
        if ($file == null || strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            throw new FileException("Un problème est survenue lors du chargement du CV, assurez-vous qu'il s'agit bien d'un fichier PDF.");
        }

        $targetDir = $this->kernel->getProjectDir() . "/upload/candidacies/" . $freelance->getId();
        if (!$fileSystem->exists($targetDir)) {
            $fileSystem->mkdir($targetDir);
        }
        $newFileName = uuid_create() . ".pdf";


        $file->move($targetDir, $newFileName);
        $filePath = $targetDir . "/" . $newFileName;

        $candidacy->setFreelance($freelance);
        $candidacy->setMission($mission);
        $candidacy->setCvFilePath($filePath);
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

    public function toggleCandidacyStatus(Candidacy $candidacy, CandidacyStatus $status): void 
    {
        
    }
}
