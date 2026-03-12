<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('username');
            $table->string('password');
            $table->string('image_path');
            $table->string('api_token');
            $table->string('fcm_id');
            $table->bigInteger('tipe_user_id')->unsigned();
            $table->bigInteger('unit_kerja_id')->unsigned();
            $table->string('jenis_kelamin');
            $table->string('alamat');
            $table->string('agama');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('no_hp');
            $table->foreign('tipe_user_id')->references('id')->on('tipe_users');
            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas');
            $table->softDeletes();
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
