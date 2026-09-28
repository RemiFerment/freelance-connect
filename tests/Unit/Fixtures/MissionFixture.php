<?php

namespace App\Tests\Unit\Fixtures;

use App\Entity\Mission;
use App\Entity\MissionStatus;
use App\Entity\User;
use DateTimeImmutable;

class MissionFixture
{
    public static function createFakeMission(MissionStatus $status, User $client): Mission
    {
        return new Mission()
            ->setTitle("Mission fixture")
            ->setDescription("Test.")
            ->setBudget(1)
            ->setClient($client)
            ->setStatus($status)
            ->setDeadline(new \DateTime())
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable());
    }
}
