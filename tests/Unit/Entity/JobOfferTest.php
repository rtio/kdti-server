<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\JobOffer;
use App\Tests\TestCase;

class JobOfferTest extends TestCase
{
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');
    }

    public function test_string_representation(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Site Reliability Engineering Manager',
        ]);

        $this->assertEquals("#{$jobOffer->getId()} Site Reliability Engineering Manager", (string) $jobOffer);
    }

    public function test_generate_a_slug_based_on_title(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Senior PHP Developer',
        ]);

        $this->assertEquals('senior-php-developer', $jobOffer->getSlug());
    }

    public function test_generated_slug_should_be_unique(): void
    {
        $this->factory->create(JobOffer::class, ['title' => 'Full Stack Engineer']);

        $jobOffer = $this->factory->create(JobOffer::class, ['title' => 'Full Stack Engineer']);

        $this->assertEquals('full-stack-engineer-1', $jobOffer->getSlug());
    }

    public function test_do_not_override_existing_slug(): void
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Test Analyst (Senior)',
        ]);

        $jobOffer->setTitle('Senior Test Analyst');
        $this->entityManager->flush();

        $this->assertEquals('test-analyst-senior', $jobOffer->getSlug());
    }
}
