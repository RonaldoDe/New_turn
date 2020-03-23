<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UserRole;

class CreateUserHasRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            #User relation
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            #Role relation
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('role');

            $table->timestamps();
        });

        $user_role = UserRole::create([
            'user_id' => 1,
            'role_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_has_role');
    }
}
