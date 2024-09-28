<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('users_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('device')->nullable();
            $table->boolean('aut')->nullable();
            $table->boolean('deny')->nullable();
            $table->string('ip')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_devices');
    }
}
