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

        \App\Models\Invoice::truncate();
        \App\Models\Cart::truncate();
        \App\Models\Promo::truncate();
        \App\Models\ProductReview::truncate();

        $date = new DateTime();
        factory(\App\Models\Promo::class, 1)->create([
            'end_date' => $date->format('Y-m-d'),
        ]);

        factory(\App\Models\Cart::class, 6)->create();

        // without promo
        factory(\App\Models\Cart::class, 2)->create(['status' => 'pending', 'qty' => 2, 'invoice_id' => 1])->each(
            function($cart) {
                factory(\App\Models\Invoice::class, 1)->create([
                    'total' => ($cart->price * $cart->qty),
                    'promo_code' => NULL
                ]);
                factory(\App\Models\ProductReview::class, 1)->create([
                    'product_id' => $cart->product_id,
                    'user_id' => $cart->user_id
                ]);
            }
        );

        // with promo
        $date = new DateTime('2019-12-31');
        factory(\App\Models\Promo::class, 1)->create(['end_date' => $date->format('Y-m-d')])->each(
            function ($promo) {
                factory(\App\Models\Cart::class, 2)->create(['status' => 'pending', 'qty' => 2, 'invoice_id' => 2])->each(
                    function ($cart) use ($promo) {
                        factory(\App\Models\Invoice::class, 1)->create([
                            'total' => ($cart->price * $cart->qty) - $promo->value,
                            'promo_code' => $promo->code
                        ]);
                        factory(\App\Models\ProductReview::class, 1)->create([
                            'product_id' => $cart->product_id,
                            'user_id' => $cart->user_id
                        ]);
                    }
                );
            }
        );

        // Enable it back
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
