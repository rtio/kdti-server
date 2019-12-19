<?php

namespace App\Repository;

use App\Entity\JobOffer;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findAllTaggedJobsApprovedById(int $tagId): array
    {
        $tag = $this->createQueryBuilder('t')
            ->innerJoin('t.jobOffers', 'j')
            ->andWhere('j.status = :status')
            ->andWhere('t.id = :id')
            ->setParameter('id', $tagId)
            ->setParameter('status', JobOffer::STATUS_APPROVED)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return $tag ? $tag->getJobOffers()->toArray() : [];
    }

    public function findAllTaggedJobsApprovedBySlug(string $slug): array
    {
        $tag = $this->createQueryBuilder('t')
            ->innerJoin('t.jobOffers', 'j')
            ->andWhere('j.status = :status')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->setParameter('status', JobOffer::STATUS_APPROVED)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return $tag ? $tag->getJobOffers()->toArray() : [];
    }
}
