<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLupaAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lupa_absens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal_lupa');
            $table->string('status');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('atasan_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('atasan_id')->references('id')->on('users');
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
        Schema::dropIfExists('lupa_absens');
    }
}
