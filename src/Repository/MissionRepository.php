<?php

namespace App\Repository;

use App\Entity\Mission;
use App\Entity\MissionStatus;
use App\Enum\MissionStatusEnum;
use App\Enum\CandidacyStatusEnum;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mission>
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

    /**
     * Retrieves missions filtered by client and status.
     *
     * @param User|null $client The client to filter by, or null for all clients.
     * @param array $status Status to filter missions. (add the MissionStatus entity here)
     *
     * @return Mission[] Matching mission objects, ordered by creation date.
     */
    public function findAllMissionsByStatus(?User $client, array $status): array
    {
        $qb = $this->createQueryBuilder("m");

        if ($client !== null) {
            $qb->andWhere("m.client = :client")
                ->setParameter("client", $client);
        }

        if (!empty($status)) {
            $qb->andWhere("m.status IN (:statuses)")
                ->setParameter("statuses", $status);
        }

        return $qb->orderBy("m.createdAt", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findOpenMissions(): array
    {
        return $this->createQueryBuilder('m')
        ->join('m.status', 's')
        ->andWhere('s.code = :status')
        ->setParameter('status', MissionStatusEnum::PENDING->value)
        ->orderBy('m.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function findOpenMissionsFiltered(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.status', 's')
            ->andWhere('s.code = :status')
            ->setParameter('status', MissionStatusEnum::PENDING->value);

        if (!empty($filters['category'])) {
            $qb->join('m.categories', 'c')
                ->andWhere("c= :category")
                ->setParameter("category", $filters['category']);
        }

        if (!empty($filters['language'])) {
            $qb->andWhere('m.language = :language')
                ->setParameter('language', $filters['language']);
        }

        if (!empty($filters['minBudget'])) {
            $qb->andWhere('m.budget >= :minBudget')
                ->setParameter('minBudget', $filters['minBudget']);
        }

         if (!empty($filters['maxBudget'])) {
            $qb->andWhere('m.budget <= :maxBudget')
                ->setParameter('maxBudget', $filters['maxBudget']);
        }

        $qb->distinct();

        return $qb
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findCompletedMissionsByFreelance(User $freelance): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.candidacies', 'c')
            ->join('m.status', 's')
            ->join('c.status', 'cs')
            ->andWhere('c.freelance = :freelance')
            ->andWhere('cs.code = :candidacyStatus')
            ->andWhere('s.code = :missionStatus')
            ->setParameter('freelance', $freelance)
            ->setParameter('candidacyStatus', CandidacyStatusEnum::ACCEPTED->value)
            ->setParameter('missionStatus', MissionStatusEnum::COMPLETED->value)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllFiltered(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('m');

        if (!empty($filters['client'])) {
            $qb->andWhere('m.client = :client')
                ->setParameter('client', $filters['client']);
        }

        if (!empty($filters['freelance'])) {
            $qb->join('m.candidacies', 'c')
                ->andWhere('c.freelance = :freelance')
                ->setParameter('freelance', $filters['freelance']);
        }

        if (!empty($filters['categories'])) {
            $qb->join('m.categories', 'cat')
                ->andWhere("cat IN (:categories)")
                ->setParameter("categories", $filters['categories']);
        }

        if (!empty($filters['startDate'])) {
            $qb->andWhere('m.createdAt >= :startDate')
                ->setParameter('startDate', $filters['startDate']);
        }

        if (!empty($filters['endDate'])) {
            $qb->andWhere('m.createdAt <= :endDate')
                ->setParameter('endDate', $filters['endDate']);
        }

        if (!empty($filters['minBudget'])) {
            $qb->andWhere('m.budget >= :minBudget')
                ->setParameter('minBudget', $filters['minBudget']);
        }

         if (!empty($filters['maxBudget'])) {
            $qb->andWhere('m.budget <= :maxBudget')
                ->setParameter('maxBudget', $filters['maxBudget']);
        }

        $qb->distinct();

        return $qb
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentOpenMissions(): array
{
    return $this->createQueryBuilder('m')
        ->addSelect('c', 'l')
        ->leftJoin('m.categories', 'c')
        ->leftJoin('m.language', 'l')
        ->join('m.status', 's')
        ->andWhere('s.code = :status')
        ->setParameter('status', MissionStatusEnum::PENDING->value)
        ->orderBy('m.createdAt', 'DESC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Mission[] Returns an array of Mission objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Mission
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
