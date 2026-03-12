<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJabatanUserUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_user_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('surat_internal_id')->unsigned();
            $table->foreign('surat_internal_id')->references('id')->on('surat_internals');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('jabatan_id')->unsigned();
            $table->foreign('jabatan_id')->references('id')->on('jabatans');
            $table->bigInteger('unit_kerja_id')->unsigned();
            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas');
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
        Schema::dropIfExists('jabatan_user_units');
    }
}
