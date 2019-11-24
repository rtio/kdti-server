<?php

declare(strict_types=1);

namespace App\Tests\Features;

use App\Tests\TestCase;
use App\Entity\JobOffer;
use Symfony\Component\HttpFoundation\Response;

class DisplaySingleJobOfferTest extends TestCase
{
    public function test_display_a_single_job_offer(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Site Reliability Engineering Manager',
            'description' => 'You will support engineers developing services and infrastructure.',
            'company' => 'Wikimedia Foundation, Inc',
            'seniorityLevel' => 'Senior',
            'salary' => 4500,
            'status' => JobOffer::STATUS_APPROVED,
        ]);

        $this->client->request('GET', "/api/job-offer/{$jobOffer->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    public function test_displaying_non_approved_job_offers_causes_404(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Database Reliability Engineer',
            'description' => "Our Database Reliability Engineering (DRE) team supports Yelp’s database infrastructure",
            'company' => 'Yelp’s',
            'seniorityLevel' => 'Mid-Senior',
            'salary' => 3200,
            'status' => JobOffer::STATUS_PENDING_REVIEW,
        ]);

        $this->client->request('GET', "/api/job-offer/{$jobOffer->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_NOT_FOUND, $response);
    }
}
