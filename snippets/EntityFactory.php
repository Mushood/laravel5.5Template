<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Entity::class, function (Faker $faker) {
    $title = $faker->word;

    $image = new \App\Models\Image();
    $image->name = 'default.jpg';
    $image->alt = 'default';
    $image->save();

    return [
        'title' => $title,
        'body' => $faker->paragraph,
        'image_id' => $image->id,
        'slug' => $title,
    ];
});
