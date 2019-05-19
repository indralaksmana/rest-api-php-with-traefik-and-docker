<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCreateInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id');

            $table->double('total');
            $table->integer('user_id')->unsigned();
            $table->text('address')->nullable();
            $table->string('status')->default('waiting payment')
                  ->comment = 'waiting payment, packaging, sent, returned';
            $table->string('method')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))
                  ->attributes(DB::raw('on update CURRENT_TIMESTAMP'))
                  ->extra(DB::raw('ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')
                  ->references('id')->on('users')
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
        Schema::drop('invoice');
    }
}
