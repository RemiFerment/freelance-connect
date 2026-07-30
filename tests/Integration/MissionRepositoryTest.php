<?php

namespace App\Tests\Integration;

use App\Entity\Mission;
use App\Entity\MissionStatus;
use App\Entity\User;
use App\Enum\MissionStatusEnum;
use App\Repository\MissionRepository;
use App\Repository\MissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MissionRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private MissionRepository $rep;
    private MissionStatusRepository $repStatus;
    private User $fakeClient;

    #[Override]
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->rep = $container->get(MissionRepository::class);
        $this->repStatus = $container->get(MissionStatusRepository::class);
        $this->fakeClient = new User();
        $this->fakeClient->setEmail("test@test.test")
            ->setAdress("")
            ->setCity("")
            ->setCountry("")
            ->setFirstname("test")
            ->setPostalCode("")
            ->setCompanyName("")
            ->setLastname("test")
            ->setPhone("")
            ->setPassword("test")
            ->setRoles(["ROLE_CLIENT"])
        ;
    }

    public function testFindMissionByStatus(): void
    {
        //Arrange
        $pendingStatut = $this->repStatus->findOneByCode(MissionStatusEnum::PENDING->value);
        $completedStatut = $this->repStatus->findOneByCode(MissionStatusEnum::COMPLETED->value);
        $inProgressStatut = $this->repStatus->findOneByCode(MissionStatusEnum::IN_PROGRESS->value);
        $canceledStatut = $this->repStatus->findOneByCode(MissionStatusEnum::CANCELED->value);

        $this->em->persist($this->fakeClient);
        $this->em->flush();

        $this->em->persist($this->createMission("test", $pendingStatut, $this->fakeClient));
        $this->em->persist($this->createMission("test2", $completedStatut, $this->fakeClient));
        $this->em->persist($this->createMission("test3", $completedStatut, $this->fakeClient));
        $this->em->persist($this->createMission("test4", $pendingStatut, $this->fakeClient));
        $this->em->persist($this->createMission("test5", $inProgressStatut, $this->fakeClient));
        $this->em->persist($this->createMission("test6", $canceledStatut, $this->fakeClient));
        $this->em->persist($this->createMission("test7", $pendingStatut, $this->fakeClient));
        $this->em->flush();

        //Act
        $pendingMissions = $this->rep->findAllMissionsByStatus($this->fakeClient, [$pendingStatut]);
        $completedMissions = $this->rep->findAllMissionsByStatus($this->fakeClient, [$completedStatut]);
        $canceledMissions = $this->rep->findAllMissionsByStatus($this->fakeClient, [$canceledStatut]);
        $inProgressMissions = $this->rep->findAllMissionsByStatus($this->fakeClient, [$inProgressStatut]);
        $showableMissions = $this->rep->findAllMissionsByStatus($this->fakeClient, [$completedStatut, $inProgressStatut, $pendingStatut]);

        //Assert
        $this->assertEquals(3, count($pendingMissions));
        $this->assertEquals(2, count($completedMissions));
        $this->assertEquals(1, count($canceledMissions));
        $this->assertEquals(1, count($inProgressMissions));
        $this->assertEquals(6, count($showableMissions));
    }

    public function createMission(string $title, MissionStatus $status, User $client): Mission
    {
        $mission = new Mission();
        return $mission->setTitle($title)
            ->setDescription("test description")
            ->setBudget(10.00)
            ->setDeadline(new \DateTime())
            ->setStatus($status)
            ->setClient($client)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }
}
