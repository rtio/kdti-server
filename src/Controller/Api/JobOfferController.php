<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class JobOfferController extends AbstractController
{
    private $repository;

    public function __construct(JobOfferRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/api/job-offer", name="api_job_offer_index")
     */
    public function index(): Response
    {
        $jobOffers = $this->repository->findAllApproved();

        return $this->json($jobOffers, Response::HTTP_OK, [], ['groups' => ['list']]);
    }

    /**
     * @Route("/api/job-offer/{jobOfferId}", name="api_job_offer_display")
     */
    public function display(int $jobOfferId): Response
    {
        $jobOffer = $this->repository->findApprovedById($jobOfferId);

        if (null === $jobOffer) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json($jobOffer, Response::HTTP_OK, [], [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (JobOffer $jobOffer) {
                return $jobOffer->getTitle();
            },
            'groups' => ['detail']
        ]);
    }
}
