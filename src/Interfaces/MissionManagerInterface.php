<?php

namespace App\Interfaces;

use App\Entity\Mission;
use App\Entity\User;

interface MissionManagerInterface
{
    public function create(Mission $mission, User $currentUser): bool;
}