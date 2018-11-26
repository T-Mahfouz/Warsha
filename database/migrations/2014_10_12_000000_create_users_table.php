<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('whatsapp')->unique()->nullable();
            $table->string('username');
            $table->string('image');
            $table->string('password');
            $table->string('code')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->boolean('status')->default(1);
            $table->string('firebase')->nullable();
            $table->timestamp('last_seen')->default(Carbon::now());
            $table->foreign('role_id')->references('id')->on('roles');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
