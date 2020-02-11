<?php

declare(strict_types=1);

namespace App\Tests\Feature\JobOffer;

use App\Entity\Company;
use App\Tests\Support\HasAuthentication;
use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class JobOfferCreateActionTest extends TestCase
{
    use HasAuthentication;

    protected function setUp(): void
    {
        parent::setUp();

        $company = $this->factory->create(Company::class, [
            'email' => 'tony@starkindustries.com',
            'password' => 'lovepepper',
        ]);

        $this->authenticate([
            'username' => 'tony@starkindustries.com',
            'password' => 'lovepepper',
        ]);
    }

    public function testCreateJobOfferSuccessfully(): void
    {
        $this->client->request('POST', '/api/job-offers/create', [], [], [], json_encode([
            'title' => 'Jarvis Lead Engineer',
            'description' => 'Lead the team of Jarvis systems.',
            'seniorityLevel' => 'Tech Lead',
            'minimumSalary' => 9750,
            'maximumSalary' => 10000,
            'allowRemote' => true,
        ]));

        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_CREATED, $response);
        $this->assertResponseMatchesSnapshot($response);
    }
}
