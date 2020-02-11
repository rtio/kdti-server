<?php

declare(strict_types=1);

namespace App\Service\JobOffer;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;

class JobOfferCreate
{
    /**
     * @var JobOfferRepository
     */
    protected JobOfferRepository $repository;

    public function __construct(
        JobOfferRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function create(JobOffer $jobOffer): void
    {
        $this->repository->save($jobOffer);
    }
}
