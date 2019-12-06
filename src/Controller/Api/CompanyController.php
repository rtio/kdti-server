<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Entity\Company;
use App\Form\CompanyRegistrationType;
use App\Request\CompanyRegistration;
use App\Service\CompanyService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class CompanyController extends BaseController
{
    private $companyService;

    public function __construct(CompanyService $service)
    {
        $this->companyService = $service;
    }

    /**
     * @SWG\Response(
     *     response=201,
     *     description="Returns the company just created",
     *     @Model(type=Company::class, groups={"detail"})
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returns the validation error"
     * )
     * @SWG\Tag(name="Company")
     *
     * @Route("/api/company/registration", name="api_company_registration", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(CompanyRegistrationType::class, new CompanyRegistration());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $company = $this->companyService->register($form->getData());

        return $this->json(
            $company,
            Response::HTTP_CREATED,
            [],
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (Company $company) {
                    return $company->getName();
                },
                'groups' => ['detail'],
            ]);
    }
}
