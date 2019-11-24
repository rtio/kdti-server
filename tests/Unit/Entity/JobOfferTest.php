<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\JobOffer;
use App\Tests\TestCase;

class JobOfferTest extends TestCase
{
    public function test_string_representation()
    {
        $jobOffer = $this->factory->create(JobOffer::class, [
            'title' => 'Site Reliability Engineering Manager',
        ]);
        $this->assertEquals("#{$jobOffer->getId()} Site Reliability Engineering Manager", (string) $jobOffer);
    }
}
