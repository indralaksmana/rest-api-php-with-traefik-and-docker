<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCreateCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))
                  ->attributes(DB::raw('on update CURRENT_TIMESTAMP'))
                  ->extra(DB::raw('ON UPDATE CURRENT_TIMESTAMP'));

            $table->double('total');
            $table->string('status')->default('incomplete')
                  ->comment = 'incomplete, pending, processed, shipping, shipped, returned, canceled';
            $table->integer('product_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('stock')->default(1);
            $table->integer('invoice_id')->unsigned()
                  ->nullable();

            $table->foreign('product_id')
                  ->references('id')->on('product')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->foreign('invoice_id')
                  ->references('id')->on('invoice')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cart');
    }
}
