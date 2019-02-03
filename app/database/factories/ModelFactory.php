<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Shop::class, function () {
    $faker = Faker\Factory::create('fr_FR');

    $shopName = $faker->company;
    $categories = [
        1 => 'Boulangerie - Pâtisserie',
        2 => 'Fromagerie',
        3 => 'Restaurant',
        4 => 'Chocolatier',
        5 => 'Magasin spécialisé',
    ];
    $category_id = $faker->numberBetween(1, 5);
    $category_name = $categories[$category_id];

    return [
        'shop_id' => $faker->unique()->randomDigitNotNull,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'distance' => $faker->numberBetween(1, 20000),
        'name' => $shopName,
        'chain' => $shopName,
        'address' => $faker->address,
        'zipCode' => $faker->numberBetween(),
        'city' => $faker->city,
        'category_id' => $category_id,
        'category_name' => $category_name,
        'logo' => $faker->imageUrl(),
        'cover' => $faker->imageUrl(),
        'maxoffer' => $faker->randomFloat(2, 10, 50),
        'currency' => 'EUR',
    ];
});
