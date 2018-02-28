<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Entity::class, function (Faker $faker) {
    $title = $faker->word;
    return [
        'title' => $title,
        'body' => $faker->paragraph,
        'image_id' => 1,
        'order' => 0,
        'slug' => $title,
    ];
});