<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTugasDinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tugas_dinas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('tentang');
            $table->text('keterangan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('surat_internal_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('tugas_dinas');
    }
}
