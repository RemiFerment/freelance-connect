<?php

namespace App\Interfaces;

use App\Entity\Candidacy;
use App\Entity\Mission;
use App\Entity\User;

interface MissionManagerInterface
{
    /**
     * Creates a new mission and associates it with the current user.
     * @param Mission $mission
     * @param User $currentClient
     */
    public function create(Mission $mission, User $currentClient): void;

    /**
     * Edits an existing mission if the current user is the owner.
     * @param Mission $mission
     * @param User $currentClient
     */
    public function edit(Mission $mission, User $currentClient): void;

    /**
     * Cancels a mission if the current user is the owner.
     * @param Mission $mission
     * @param User $currentClient
     */
    public function cancel(Mission $mission, User $currentClient): void;

    /**
     * Accepts a candidacy for a mission if the current user is the owner.
     * @param Mission $mission
     * @param User $client
     * @param User $freelance
     */
    public function acceptCandidacy(Mission $mission, Candidacy $candidacy): void;

    /**
     * Refuses a candidacy for a mission if the current user is the owner.
     * @param Mission $mission
     * @param User $client
     * @param User $freelance
     */
    public function refuseCandidacy(Mission $mission, Candidacy $candidacy): void;

    /**
     * Sets a mission as completed.
     * @param Mission $mission
     */
    public function setMissionCompleted(Mission $mission): void;

    /**
     * [ADMIN] Deletes a mission.
     * @param Mission $mission
     */
    public function delete(Mission $mission): void;
}
