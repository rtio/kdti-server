<?php

declare(strict_types=1);

namespace App\Tests\Features;

use App\Tests\TestCase;
use App\Entity\JobOffer;
use Symfony\Component\HttpFoundation\Response;

class ListJobOffersTest extends TestCase
{
    public function test_should_list_only_approved_job_offers(): void
    {
        $this->factory->create(JobOffer::class, [
            'title' => 'Experienced Application Developer',
            'description' => 'We are seeking a Experienced Developer to join our team.',
            'company' => 'Appian',
            'seniorityLevel' => 'Senior',
            'salary' => 2750,
            'status' => JobOffer::STATUS_APPROVED,
        ]);
        $this->factory->create(JobOffer::class, [
            'title' => 'Full Stack Engineer',
            'description' => 'Our traffic has passed 100 million monthly page views and is increasing fast.',
            'company' => 'PropertyGuru',
            'seniorityLevel' => 'Mid-Senior',
            'salary' => 4750,
            'status' => JobOffer::STATUS_PENDING_REVIEW,
        ]);

        $this->client->request('GET', '/api/job-offer');
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }
}
