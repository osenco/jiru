<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Payment::class, function (Faker $faker) {
    return [

        'amount' => rand(200, 9999),
        'paid' => rand(200, 9998),
        'balance' => rand(200, 9998),
        'customer_id' => rand(1, 5),
        // 'user_id' => rand(1, 20), 
        'request'  => Str::random(7),
        'reference' => strtoupper(Str::random(7)),
        'receipt'  => strtoupper(Str::random(8))
    ];
});
