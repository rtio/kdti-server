<?php

declare(strict_types=1);

use App\Entity\Company;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(Company::class)->setDefinitions([
    'name' => Faker::company(),
    'logo' => Faker::imageUrl(400, 600),
    'address' => Faker::address(),
    'email' => Faker::email(),
    'password' => Faker::password(),
]);
