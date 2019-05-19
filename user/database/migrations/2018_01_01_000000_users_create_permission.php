<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersCreatePermission extends Migration
{
    const CONST_PERMISSION_ID = 'permission_id';
    const CONST_PERMISSION = 'permissions';
    const CONST_ROLES = 'roles';
    const CONST_CASCADE = 'cascade';
    const CONST_ROLE_ID = 'role_id';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::create($tableNames[ self::CONST_PERMISSION ], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames[ self::CONST_ROLES ], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger(self::CONST_PERMISSION_ID);
            $table->morphs('model');

            $table->foreign(self::CONST_PERMISSION_ID)
                  ->references('id')
                  ->on($tableNames[ self::CONST_PERMISSION ])
                  ->onDelete(self::CONST_CASCADE);

            $table->primary(
                [self::CONST_PERMISSION_ID, 'model_id', 'model_type']
                , 'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger(self::CONST_ROLE_ID);
            $table->morphs('model');

            $table->foreign(self::CONST_ROLE_ID)
                  ->references('id')
                  ->on($tableNames[ self::CONST_ROLES ])
                  ->onDelete(self::CONST_CASCADE);

            $table->primary([self::CONST_ROLE_ID, 'model_id', 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger(self::CONST_PERMISSION_ID);
            $table->unsignedInteger(self::CONST_ROLE_ID);

            $table->foreign(self::CONST_PERMISSION_ID)
                  ->references('id')
                  ->on($tableNames[ self::CONST_PERMISSION ])
                  ->onDelete(self::CONST_CASCADE);

            $table->foreign(self::CONST_ROLE_ID)
                  ->references('id')
                  ->on($tableNames[ self::CONST_ROLES ])
                  ->onDelete(self::CONST_CASCADE);

            $table->primary([self::CONST_PERMISSION_ID, self::CONST_ROLE_ID]);

            app('cache')->forget('spatie.permission.cache');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames[ self::CONST_ROLES ]);
        Schema::drop($tableNames[ self::CONST_PERMISSION ]);
    }
}
