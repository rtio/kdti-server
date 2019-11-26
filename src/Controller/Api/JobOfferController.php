<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\JobOffer;
use App\Request\PostJobOffer;
use App\Form\PostJobOfferType;
use App\Service\JobOfferService;
use App\Controller\BaseController;
use App\Repository\JobOfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class JobOfferController extends BaseController
{
    private $repository;
    private $jobOfferService;

    public function __construct(
        JobOfferRepository $repository,
        JobOfferService $service
    ) {
        $this->repository = $repository;
        $this->jobOfferService = $service;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/api/job-offer", name="api_job_offer_index", methods={"GET"})
     */
    public function index(): Response
    {
        $jobOffers = $this->repository->findAllApproved();

        return $this->json($jobOffers, Response::HTTP_OK, [], ['groups' => ['list']]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/api/job-offer/{jobOfferId}", name="api_job_offer_display", methods={"GET"})
     */
    public function display(int $jobOfferId): Response
    {
        $jobOffer = $this->repository->findApprovedById($jobOfferId);

        if (null === $jobOffer) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json($jobOffer, Response::HTTP_OK, [], [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (JobOffer $jobOffer) {
                return $jobOffer->getTitle();
            },
            'groups' => ['detail'],
        ]);
    }

    /**
     * @IsGranted("ROLE_COMPANY")
     * @Route("/api/job-offer", name="api_job_offer_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(PostJobOfferType::class, new PostJobOffer());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $jobOffer = $this->jobOfferService->postNewJobOffer($form->getData(), $this->getUser());

        return $this->json($jobOffer, Response::HTTP_CREATED, [], [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (JobOffer $jobOffer) {
                return $jobOffer->getTitle();
            },
            'groups' => ['detail']
        ]);
    }
}
