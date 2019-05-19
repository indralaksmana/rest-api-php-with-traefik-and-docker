<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW `view_product` AS
            SELECT `p`.*,
            (SELECT COUNT(*) FROM `cart` WHERE `product_id` = `p`.`id` AND `status` = 'shipped') AS `total_sold`,
            (SELECT COUNT(*) FROM `product_review` WHERE `product_id` = `p`.`id`) AS `total_review`,
            (SELECT `created_at` FROM `product_review` WHERE `product_id` = `p`.`id` ORDER BY `created_at` DESC LIMIT 1) AS `last_review`
            FROM `product` `p`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS `view_product`");
    }
}
