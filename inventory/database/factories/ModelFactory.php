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

//Category
$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    return [
        'name' => $faker->category,
    ];
});

//Product
$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    $store_ids = \DB::table('store')->select('id')->get();
    $store_id  = $faker->randomElement($store_ids)->id;

    return [
        'name'        => $faker->productName,
        'description' => $faker->realText(100),
        'harga'       => $faker->randomNumber(5),
        'stock'       => $faker->numberBetween(10, 20),
        'store_id'    => $store_id,
    ];
});

//ProductImage
$factory->define(App\Models\ProductImage::class, function (Faker\Generator $faker) {
    return [
        //'product_id' => $faker->numberBetween(1, 5),
        'image' => $faker->image('public/storage/images', '640', '480', 'transport', false),
    ];
});

//ProductCategory
$factory->define(App\Models\ProductCategory::class, function (Faker\Generator $faker) {
    $product_ids = \DB::table('product')->select('id')->get();
    $product_id  = $faker->randomElement($product_ids)->id;

    $category_ids = \DB::table('category')->select('id')->get();
    $category_id  = $faker->randomElement($category_ids)->id;

    return [
        'product_id'  => $product_id,
        'category_id' => $category_id,
    ];
});

//Wishlist
$factory->define(App\Models\Wishlist::class, function (Faker\Generator $faker) {

    return [
        'user_id'       => $faker->numberBetween(2, 6),
        'product_id'    => $faker->unique()->numberBetween(1, 200),
    ];
});

//Store
$factory->define(App\Models\Store::class, function (Faker\Generator $faker) {

    return [
        'user_id'       => $faker->unique()->numberBetween(7, 21),
        'name'          => $faker->company,
        'description'   => $faker->sentence(),
        'image'         => $faker->image('public/storage/images', '640', '480', 'transport', false),
    ];
});
