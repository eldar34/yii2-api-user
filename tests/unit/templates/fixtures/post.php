<?php

use Faker\Factory;

$faker = Factory::create();


return [
    'title' => $faker->jobTitle,
    'content' => $faker->realText(200, 2)
];