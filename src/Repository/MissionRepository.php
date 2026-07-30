<?php

namespace App\Repository;

use App\Entity\Mission;
use App\Entity\MissionStatus;
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
     * Finds all missions associated with a specific client and optional status filters.
     * @param User $client The client whose missions are to be retrieved.
     * @param array $status An array of status codes to filter the missions. Defaults to ["PENDING"].
     * @return Mission[] An array of Mission objects that match the criteria.
     */
    public function findAllMissionsByClient(User $client, array $status = ["PENDING"]): array
    {
        if (!in_array("ROLE_CLIENT", $client->getRoles())) {
            throw new \UnexpectedValueException("The user don't have the permission to view missions.");
        }

        $queryBuilder = $this->createQueryBuilder("m")
            ->andWhere("m.client = :id")
            ->setParameter(":id", $client->getId());
        for ($i = 0; $i < count($status); $i++) {
            $queryBuilder->andWhere("m.status = :status_" . $i)
                ->setParameter("status_" . $i, $status[$i]);
        }
        $queryBuilder->orderBy("m.createdAt", "ASC");

        return $queryBuilder->getQuery()
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
