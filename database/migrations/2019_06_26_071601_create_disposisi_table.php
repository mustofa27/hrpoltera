<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisposisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('isi_disposisi');
            $table->text('tanggal_disposisi');
            $table->text('tanggal_diterima');
            $table->text('catatan_tindak_lanjut');
            $table->bigInteger('id_surat')->unsigned();
            $table->foreign('id_surat')->references('id')->on('surats');
            $table->bigInteger('id_user_asal')->unsigned();
            $table->foreign('id_user_asal')->references('id')->on('users');
            $table->bigInteger('id_user_tujuan')->unsigned();
            $table->foreign('id_user_tujuan')->references('id')->on('users');
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
        Schema::dropIfExists('disposisis');
    }
}
