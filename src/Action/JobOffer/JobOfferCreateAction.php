<?php

declare(strict_types=1);

namespace App\Action\JobOffer;

use App\Action\AbstractAction;
use App\Input\JobOfferInput;
use App\Output\JobOfferOutput;
use App\Service\JobOffer\JobOfferCreate;
use Nette\Utils\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobOfferCreateAction extends AbstractAction
{
    /**
     * @var JobOfferCreate
     */
    protected $service;

    /**
     * @var JobOfferInput
     */
    protected $input;
    /**
     * @var JobOfferOutput
     */
    private $output;

    public function __construct(
        JobOfferInput $input,
        JobOfferCreate $service,
        JobOfferOutput $output
    ) {
        $this->service = $service;
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @IsGranted("ROLE_COMPANY")
     * @Route("/api/job-offers/create", name="api_job_offer_create_new", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
        $data = Json::decode($request->getContent(), Json::FORCE_ARRAY);
        $jobOffer = $this->input->fromCreate($data);
        $this->service->create($jobOffer);

        return JsonResponse::fromJsonString($this->output->toFullOutput($jobOffer), Response::HTTP_CREATED);
    }
}
