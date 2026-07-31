<?php

namespace App\Interfaces;

use App\Entity\Candidacy;
use App\Entity\CandidacyStatus;
use App\Entity\User;
use App\Entity\Mission;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface CandidacyManagerInterface
{
    /**
     * Apply to a mission
     *
     * @param Candidacy $candidacy
     * @param User $freelance
     * @param Mission $mission
     * @return void
     */
    public function apply(Candidacy $candidacy, User $freelance, Mission $mission, UploadedFile $file): void;

    /**
     * 
     */
    public function toggleCandidacyStatus(Candidacy $candidacy, CandidacyStatus $status): void;
}
