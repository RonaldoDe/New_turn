<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleIdToPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permission', function (Blueprint $table) {
            # Module relation
            $table->integer('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('module');
        });

        $permission = Permission::insert([[
            'name' => 'Listar usuarios',
            'description' => 'Permite listar usuarios.',
            'route' => '/list_user',
            'module_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Crear usuarios',
            'description' => 'Permite crear usuarios.',
            'route' => '/create_user',
            'module_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'modificar usuarios',
            'description' => 'Permite actualizar y eliminar usuarios.',
            'route' => '/update_user',
            'module_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Listar roles',
            'description' => 'Permite listar roles.',
            'route' => '/list_role',
            'module_id' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],[
            'name' => 'Crear roles',
            'description' => 'Permite crear, actualizar y eliminar roles.',
            'route' => '/create_role',
            'module_id' => 2,
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
        Schema::table('permission', function (Blueprint $table) {
            //
        });
    }
}
