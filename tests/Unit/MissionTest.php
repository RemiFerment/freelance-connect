<?php

namespace App\Tests\Unit;

use App\Entity\Mission;
use App\Entity\MissionStatus;
use App\Entity\User;
use App\Interfaces\MissionManagerInterface;
use App\Repository\MissionRepository;
use App\Repository\MissionStatusRepository;
use App\Services\MissionManager;
use App\Tests\Unit\Fixtures\MissionFixture;
use App\Tests\Unit\Fixtures\UserFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MissionTest extends KernelTestCase
{
    public function testSetMissionCompleted(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $missionRep = $this->createStub(MissionStatusRepository::class);
        $missionRep->method("findOneByCode")->willReturn(new MissionStatus()->setCode("COMPLETED")->setLabel("Compléter"));
        $container->set(MissionStatusRepository::class, $missionRep);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method("persist");
        $em->method("flush");
        $container->set(EntityManagerInterface::class, $em);

        $user = UserFixture::createFakeUser();
        $status = $missionRep->findOneByCode("COMPLETED");
        $mission = MissionFixture::createFakeMission($status, $user);

        $missionServices = $container->get(MissionManager::class);
        $this->assertInstanceOf(MissionManager::class, $missionServices);

        $test = $missionServices->setMissionCompleted($mission);
        $this->assertTrue($test);
    }
}
