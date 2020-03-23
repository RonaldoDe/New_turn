<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('address');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            # State relation
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('user_state');
            $table->rememberToken();
            $table->timestamps();
        });

        $user = User::create([
            'name' => 'Administrator',
            'last_name' => 'General',
            'phone' => '0000000000',
            'address' => 'cll cr nn',
            'email' => 'administrator@binar10.com',
            'password' => bcrypt('123456'),
            'state_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
