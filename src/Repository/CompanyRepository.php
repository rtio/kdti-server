<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Repository\Contracts\CompanyRepository as CompanyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class CompanyRepository extends ServiceEntityRepository implements CompanyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $company): void
    {
        $this->_em->persist($company);
        $this->_em->flush();
    }
}
