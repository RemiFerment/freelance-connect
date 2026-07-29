<?php

namespace App\Services;

use App\Entity\Mission;
use App\Entity\User;
use App\Interfaces\MissionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class MissionManager implements MissionManagerInterface
{
    public function __construct(private EntityManagerInterface $em) {}
    public function create(Mission $mission, User $currentUser): bool
    {
        $mission->setClient($currentUser);
        $this->em->persist($mission);
        $this->em->flush();
        return true;
    }
}
