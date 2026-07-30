<?php

namespace App\Repository;

use App\Entity\Candidacy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\Mission;

/**
 * @extends ServiceEntityRepository<Candidacy>
 */
class CandidacyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidacy::class);
    }

    public function findByFreelanceAndMission(User $freelance, Mission $mission): ?Candidacy
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.freelance = :freelance')
            ->andWhere('c.mission = :mission')
            ->setParameter('freelance', $freelance)
            ->setParameter('mission', $mission)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByFreelance(User $freelance): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.freelance = :freelance')
            ->setParameter('freelance', $freelance)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByMission(Mission $mission): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.mission = :mission')
            ->setParameter('mission', $mission)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Candidacy[] Returns an array of Candidacy objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Candidacy
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
