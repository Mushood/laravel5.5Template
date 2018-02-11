<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Entity::class, function (Faker $faker) {
    return [
        'title' => $faker->words,
        'body' => $faker->paragraph,
        'image_id' => 1,
        'order' => 0,
    ];
});
