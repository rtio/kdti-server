<?php

declare(strict_types=1);

namespace App\Tests\Features;

use App\Tests\TestCase;
use App\Entity\JobOffer;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\Response;

class DisplaySingleJobOfferTest extends TestCase
{
    private $company;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = $this->factory->create(Company::class, [
            'name' => 'Dunder Mifflin',
            'logo' => 'https://imageurl.com/image.png',
            'address' => 'Wall st, 11',
            'email' => 'contact@dm.com',
            'password' => '12345',
        ]);
    }

    public function test_display_a_single_job_offer(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Site Reliability Engineering Manager',
            'description' => 'You will support engineers developing services and infrastructure.',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 4000,
            'maximumSalary' => 4500,
            'status' => JobOffer::STATUS_APPROVED,
        ]);

        $this->client->request('GET', "/api/job-offers/{$jobOffer->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    public function test_displaying_non_approved_job_offers_causes_404(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Database Reliability Engineer',
            'description' => "Our Database Reliability Engineering (DRE) team supports Yelp’s database infrastructure",
            'company' => $this->company,
            'seniorityLevel' => 'Mid-Senior',
            'minimumSalary' => 3000,
            'maximumSalary' => 3200,
            'status' => JobOffer::STATUS_PENDING_REVIEW,
        ]);

        $this->client->request('GET', "/api/job-offers/{$jobOffer->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_NOT_FOUND, $response);
    }
}
