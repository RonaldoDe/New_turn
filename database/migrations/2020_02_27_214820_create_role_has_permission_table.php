<?php

use App\Models\RolePermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleHasPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_permission', function (Blueprint $table) {
            $table->bigIncrements('id');

            # Permission relation
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permission');

            # Role relation
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('role');

            $table->timestamps();
        });

        $role_permission = RolePermission::insert([[
            'permission_id' => 1,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'permission_id' => 2,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'permission_id' => 3,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'permission_id' => 4,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'permission_id' => 5,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_has_permission');
    }
}
