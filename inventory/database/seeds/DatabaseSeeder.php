<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checking because truncate() will fail
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        //Clean Storage from all images
        $files = Storage::allFiles("images");
        foreach ($files as $file) {
            if (preg_match('/(\.jpg|\.png|\.jpeg)$/', $file)) {
                Storage::delete($file);
            }
        }

        \App\Models\Store::truncate();
        \App\Models\Category::truncate();
        \App\Models\Product::truncate();
        \App\Models\ProductImage::truncate();
        \App\Models\ProductCategory::truncate();
        \App\Models\Wishlist::truncate();

        factory(\App\Models\Store::class, 10)->create();

        factory(\App\Models\Category::class, 20)->create()->each(
            function ($category) {
                factory(\App\Models\Product::class, 10)->create()->each(
                    function ($product) use ($category) {
                        factory(App\Models\ProductImage::class)->create(['product_id' => $product->id]);
                        factory(\App\Models\ProductCategory::class)->create([
                            'category_id' => $category->id,
                            'product_id'  => $product->id
                        ]);
                    }
                );
            }
        );

        factory(\App\Models\Wishlist::class, 50)->create();

        // Enable it back
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
