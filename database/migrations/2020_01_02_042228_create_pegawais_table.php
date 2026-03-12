<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nip');
            $table->string('nik');
            $table->bigInteger('pangkat_id')->unsigned();
            $table->bigInteger('golongan_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('status_nikah');
            $table->bigInteger('shift_id')->unsigned();
            $table->string('npwp');
            $table->integer('is_serdos');
            $table->integer('is_remun');
            $table->bigInteger('kampus_id')->unsigned();
            $table->string('kode_device')->nullable();
            $table->bigInteger('atasan_langsung_id')->unsigned();
            $table->foreign('pangkat_id')->references('id')->on('pangkats');
            $table->foreign('golongan_id')->references('id')->on('golongans');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('kampus_id')->references('id')->on('kampuses');
            $table->foreign('atasan_langsung_id')->references('id')->on('users');
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
        Schema::dropIfExists('pegawais');
    }
}
