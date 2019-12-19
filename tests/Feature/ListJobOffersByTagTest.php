<?php

declare(strict_types=1);

namespace App\Tests\Features;

use App\Entity\Company;
use App\Entity\Tag;
use App\Tests\TestCase;
use App\Entity\JobOffer;
use Symfony\Component\HttpFoundation\Response;

class ListJobOffersByTagTest extends TestCase
{
    private $entityManager;
    private $company;

    public function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');
        $this->company = $this->factory->create(Company::class, [
            'name' => 'Dunder Mifflin',
            'logo' => 'https://imageurl.com/image.png',
            'address' => 'Wall st, 11',
            'email' => 'contact@dm.com',
            'password' => '12345',
        ]);
    }

    public function test_should_list_only_approved_job_offers_by_id(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Experienced Application Developer',
            'description' => 'We are seeking a Experienced Developer to join our team.',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 2750,
            'maximumSalary' => 3000,
            'status' => JobOffer::STATUS_APPROVED,
        ]);

        $tag = $this->factory->create(Tag::class, [
            'name' => 'php'
        ]);

        $jobOffer->addTag($tag);
        $this->entityManager->flush();

        $this->client->request('GET', "/api/tags/{$tag->getId()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }

    public function test_should_list_only_approved_job_offers_by_slug(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Mobile Developer',
            'description' => 'We are seeking a Mobile Developer',
            'company' => $this->company,
            'seniorityLevel' => 'Senior',
            'minimumSalary' => 2000,
            'maximumSalary' => 3000,
            'status' => JobOffer::STATUS_APPROVED,
        ]);

        $tag = $this->factory->create(Tag::class, [
            'name' => 'php'
        ]);

        $jobOffer->addTag($tag);
        $this->entityManager->flush();

        $this->client->request('GET', "/api/tags/slug/{$tag->getSlug()}");
        $response = $this->client->getResponse();

        $this->assertHttpStatusCode(Response::HTTP_OK, $response);
        $this->assertResponseMatchesSnapshot($response);
    }
}
