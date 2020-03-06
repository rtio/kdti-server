<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use App\Request\PostJobOffer;

final class JobOfferService
{
    private JobOfferRepository $repository;

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
