<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\JobOffer;
use App\Entity\Tag;
use App\Tests\TestCase;

class TagTest extends TestCase
{
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');
    }

    public function test_string_representation()
    {
        $tag = $this->factory->create(Tag::class, [
            'name' => 'PHP',
        ]);

        $this->assertEquals("#{$tag->getId()} PHP", (string) $tag);
    }

    public function test_generate_a_slug_based_on_title(): void
    {
        $tag = $this->factory->create(Tag::class, [
            'name' => 'Ruby On Rails',
        ]);

        $this->assertEquals('ruby-on-rails', $tag->getSlug());
    }

    public function test_generated_slug_should_be_unique(): void
    {
        $this->factory->create(Tag::class, [
            'name' => 'Ruby On Rails',
        ]);

        $tag = $this->factory->create(Tag::class, [
            'name' => 'Ruby On Rails',
        ]);

        $this->assertEquals('ruby-on-rails-1', $tag->getSlug());
    }

    public function test_do_not_override_existing_slug(): void
    {
        $jobOffer = $this->factory->create(Tag::class, [
            'name' => 'Python',
        ]);

        $jobOffer->setName('Java');
        $this->entityManager->flush();

        $this->assertEquals('python', $jobOffer->getSlug());
    }
}
