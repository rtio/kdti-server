<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Entity\Company;
use App\Tests\TestCase;
use App\Repository\CompanyRepository;
use App\Tests\Support\HasAuthentication;
use Symfony\Component\HttpFoundation\Response;

class PostingAJobOfferTest extends TestCase
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

    public function test_a_company_can_post_a_job_offer(): void
    {
        $this->client->request('POST', '/api/job-offers', [], [], [], json_encode([
            'title' => 'Jarvis Lead Engineer',
            'description' => 'Lead the team of Jarvis systems.',
            'seniorityLevel' => 'Tech Lead',
            'salary' => 9750,
        ]));
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_CREATED, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    /**
     * @dataProvider requiredFields
     */
    public function test_validate_required_fields(string $field): void
    {
        $this->client->request('POST', '/api/job-offers', [], [], [], json_encode([
            'title' => '',
            'description' => '',
            'seniorityLevel' => '',
            'salary' => '',
        ]));
        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertHttpStatusCode(Response::HTTP_BAD_REQUEST, $response);
        $this->assertContains('This value should not be blank.', $responseBody['errors'][$field]);
    }

    public function test_validate_salary_is_integer(): void
    {
        $this->client->request('POST', '/api/job-offers', [], [], [], json_encode([
            'salary' => 'string',
        ]));
        $response = $this->client->getResponse();
        $responseBody = json_decode($response->getContent(), true);

        $this->assertHttpStatusCode(Response::HTTP_BAD_REQUEST, $response);
        $this->assertContains('This value is not valid.', $responseBody['errors']['salary']);
    }

    public function requiredFields(): iterable
    {
        yield ['title'];
        yield ['description'];
        yield ['seniorityLevel'];
        yield ['salary'];
    }
}
