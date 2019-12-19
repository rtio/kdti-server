<?php

declare(strict_types=1);

use App\Entity\Tag;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(Tag::class)->setDefinitions([
    'name' => Faker::randomElement('php', 'js', 'python'),
]);
