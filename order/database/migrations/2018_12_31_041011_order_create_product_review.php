<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCreateProductReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('review');
            $table->integer('rating')->default(1);

            $table->foreign('product_id')
                  ->references('id')->on('product')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_review');
    }
}
