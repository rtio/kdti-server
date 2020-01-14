<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Entity\Company;
use App\Form\CompanyRegistrationType;
use App\Repository\JobOfferRepository;
use App\Request\CompanyRegistration;
use App\Service\CompanyService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/api/company", name="api_company_")
 */
final class CompanyController extends BaseController
{
    private CompanyService $companyService;
    private PaginatorInterface $paginator;
    private JobOfferRepository $jobOfferRepository;

    public function __construct(
        JobOfferRepository $jobOfferRepository,
        PaginatorInterface $paginator,
        CompanyService $service
    ) {
        $this->companyService = $service;
        $this->paginator = $paginator;
        $this->jobOfferRepository = $jobOfferRepository;
    }

    /**
     * @Route("/registration", name="registration", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(CompanyRegistrationType::class, new CompanyRegistration());
        $form->submit(json_decode($request->getContent(), true));

        if (! $form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $company = $this->companyService->register($form->getData());

        return $this->json($company, Response::HTTP_CREATED, [], [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn(Company $company) => $company->getName(),
            'groups' => ['detail'],
        ]);
    }

    /**
     * @Route("/job-offers", name="job_offers", methods={"GET"})
     * @Security("is_granted('ROLE_COMPANY')")
     */
    public function jobOffers(Request $request)
    {
        $jobOffers = $this->paginator->paginate(
            $this->jobOfferRepository->findByCompany($this->getUser()),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->json($jobOffers, Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['admin'],
            AbstractNormalizer::CALLBACKS => [
                'publishedAt' => fn($innerObject) => $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : ''
            ],
        ]);
    }
}
