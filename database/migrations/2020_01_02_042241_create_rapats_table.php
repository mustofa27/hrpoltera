<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRapatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('deskripsi');
            $table->string('notula_file');
            $table->bigInteger('pj_rapat_id')->unsigned();
            $table->date('tanggal');
            $table->bigInteger('surat_internal_id')->unsigned();
            $table->foreign('pj_rapat_id')->references('id')->on('users');
            $table->foreign('surat_internal_id')->references('id')->on('surat_internals');
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
        Schema::dropIfExists('rapats');
    }
}
