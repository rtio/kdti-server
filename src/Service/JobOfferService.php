<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Request\PostJobOffer;
use App\Request\CompanyRegistration;
use App\Repository\JobOfferRepository;

final class JobOfferService
{
    private $repository;

    public function __construct(JobOfferRepository $repository)
    {
        $this->repository = $repository;
    }

    public function postNewJobOffer(PostJobOffer $data, Company $company): JobOffer
    {
        $jobOffer = JobOffer::createFromPost($data, $company);

        $this->repository->save($jobOffer);

        return $jobOffer;
    }
}
