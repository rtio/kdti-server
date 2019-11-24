<?php

declare(strict_types=1);

use App\Entity\JobOffer;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(JobOffer::class)->setDefinitions([
    'title' => Faker::jobTitle(),
    'description' => Faker::text(),
    'company' => "entity|App\Entity\Company",
    'seniorityLevel' => Faker::word(),
    'salary' => Faker::numberBetween(1000, 5000),
    'status' => JobOffer::STATUS_APPROVED,
]);
