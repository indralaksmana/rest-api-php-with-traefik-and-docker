<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InventoryCreateProductCategory extends Migration
{
    const CONST_CASCADE = 'CASCADE';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('product')->onUpdate(self::CONST_CASCADE)->onDelete(self::CONST_CASCADE);
            $table->foreign('category_id')->references('id')->on('category')->onUpdate(self::CONST_CASCADE)->onDelete(self::CONST_CASCADE);
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
        Schema::dropIfExists('product_category');
    }
}
