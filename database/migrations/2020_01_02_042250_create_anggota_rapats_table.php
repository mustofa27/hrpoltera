<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaRapatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_rapats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('rapat_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('rapat_id')->references('id')->on('rapats');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('anggota_rapats');
    }
}
