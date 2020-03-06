<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Tests\Support\HasAuthentication;
use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ListJobOffersByCompanyTest extends TestCase
{
    use HasAuthentication;

    public function test_show_jobs_to_company_authenticated(): void
    {
        $this->factory->create(Company::class, [
            'email' => 'tony@starkindustries.com',
            'password' => 'lovepepper',
        ]);

        $this->authenticate([
            'username' => 'tony@starkindustries.com',
            'password' => 'lovepepper',
        ]);

        $this->client->request('POST', '/api/job-offers', [], [], [], json_encode([
            'title' => 'Mobile Developer',
            'description' => 'We are seeking a Mobile Developer',
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 2000,
            'maximumSalary' => 3000,
            'allowRemote' => true,
        ]));

        $this->client->request('GET', '/api/company/job-offers');
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    public function test_not_show_jobs_without_company_authenticated(): void
    {
        $company = $this->factory->create(Company::class);

        $this->factory->create(JobOffer::class, [
            'title' => 'Mobile Developer',
            'description' => 'We are seeking a Mobile Developer',
            'company' => $company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 2000,
            'maximumSalary' => 3000,
            'status' => JobOffer::STATUS_APPROVED,
            'allowRemote' => true,
        ]);

        $this->client->request('GET', '/api/company/job-offers');
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_UNAUTHORIZED, $response);
    }
}
