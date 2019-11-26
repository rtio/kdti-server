<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }

    public function save(JobOffer $jobOffer): void
    {
        $this->_em->persist($jobOffer);
        $this->_em->flush();
    }

    public function findAllApproved(): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.status = :status')
            ->setParameter('status', JobOffer::STATUS_APPROVED)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findApprovedById(int $jobOfferId): ?JobOffer
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :id')
            ->andWhere('j.status = :status')
            ->setParameter('id', $jobOfferId)
            ->setParameter('status', JobOffer::STATUS_APPROVED)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
