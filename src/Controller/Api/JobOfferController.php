<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


final class JobOfferController extends AbstractController
{
    private $repository;
    private $paginator;

    public function __construct(JobOfferRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/api/job-offers", name="api_job_offer_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $jobOffers = $this->paginator->paginate(
            $this->repository->findAllApproved(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->json($jobOffers, Response::HTTP_OK, [], ['groups' => ['list']]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/api/job-offers/{jobOfferId}", name="api_job_offer_display")
     * @param int $jobOfferId
     * @return Response
     */
    public function display(int $jobOfferId): Response
    {
        $jobOffer = $this->repository->findApprovedById($jobOfferId);

        if (null === $jobOffer) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json($jobOffer, Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['detail']
        ]);
    }
}
