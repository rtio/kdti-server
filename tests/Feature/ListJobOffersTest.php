<?php

declare(strict_types=1);

namespace App\Tests\Features;

use App\Entity\Company;
use App\Tests\TestCase;
use App\Entity\JobOffer;
use Symfony\Component\HttpFoundation\Response;

class ListJobOffersTest extends TestCase
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

    public function test_should_list_only_approved_job_offers(): void
    {
        $this->factory->create(JobOffer::class, [
            'title' => 'Experienced Application Developer',
            'description' => 'We are seeking a Experienced Developer to join our team.',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 2750,
            'maximumSalary' => 3000,
            'status' => JobOffer::STATUS_APPROVED,
            'hiringType' => JobOffer::HIRING_TYPE_CLT,
        ]);
        $this->factory->create(JobOffer::class, [
            'status' => JobOffer::STATUS_PENDING_REVIEW,
            'hiringType' => JobOffer::HIRING_TYPE_CLT,
        ]);

        $this->client->request('GET', '/api/job-offers');
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }
}
