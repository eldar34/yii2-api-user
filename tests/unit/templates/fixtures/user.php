<?php

use Faker\Factory;

$faker = Factory::create();


return [
    'username' => $faker->firstName,
    'password_hash' => Yii::$app->getSecurity()->generatePasswordHash($faker->regexify('[A-z0-9_-]{}')),
    'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
    'email' => $faker->email,
    'verification_token' => Yii::$app->getSecurity()->generateRandomString(),
    'status' => 10,
    'created_at' => $faker->unixTime(),
    'updated_at' => $faker->unixTime()
];