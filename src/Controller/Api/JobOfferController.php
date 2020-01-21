<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\JobOffer;
use App\Request\PostJobOffer;
use App\Form\PostJobOfferType;
use App\Service\JobOfferService;
use App\Controller\BaseController;
use App\Repository\JobOfferRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class JobOfferController extends BaseController
{
    private JobOfferRepository $repository;
    private PaginatorInterface $paginator;
    private JobOfferService $jobOfferService;

    public function __construct(
        JobOfferRepository $repository,
        PaginatorInterface $paginator,
        JobOfferService $service
    ) {
        $this->repository = $repository;
        $this->paginator = $paginator;
        $this->jobOfferService = $service;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @Route("/api/job-offers", name="api_job_offer_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $jobOffers = $this->paginator->paginate(
            $this->repository->findAllApproved(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->json($jobOffers, Response::HTTP_OK, [], [
            AbstractNormalizer::GROUPS => ['list'],
            AbstractNormalizer::CALLBACKS => [
                'publishedAt' => fn($innerObject) => $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : ''
            ],
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @Route("/api/job-offers/{id<\d+>}", name="api_job_offer_display", methods={"GET"})
     * @Entity("jobOffer", expr="repository.findApprovedById(id)")
     */
    public function display(JobOffer $jobOffer): Response
    {
        return $this->json($jobOffer, Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['detail'],
            AbstractNormalizer::CALLBACKS => [
                'publishedAt' => fn($innerObject) => $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : ''
            ],
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @Route("/api/job-offers/slug/{slug}", name="api_job_offer_display_by_slug", methods={"GET"})
     * @Entity("jobOffer", expr="repository.findApprovedBySlug(slug)")
     */
    public function displayBySlug(JobOffer $jobOffer): Response
    {
        return $this->json($jobOffer, Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['detail'],
            AbstractNormalizer::CALLBACKS => [
                'publishedAt' => fn($innerObject) => $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : ''
            ],
        ]);
    }

    /**
     * @IsGranted("ROLE_COMPANY")
     *
     * @Route("/api/job-offers", name="api_job_offer_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(PostJobOfferType::class, new PostJobOffer());
        $form->submit(json_decode($request->getContent(), true));

        if (! $form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $jobOffer = $this->jobOfferService->postNewJobOffer($form->getData(), $this->getUser());

        return $this->json($jobOffer, Response::HTTP_CREATED, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['detail'],
        ]);
    }
}
