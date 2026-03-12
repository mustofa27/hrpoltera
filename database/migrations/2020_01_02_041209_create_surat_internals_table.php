<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuratInternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_internals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_surat');
            $table->date('tanggal_surat');
            $table->bigInteger('jenis_surat_id')->unsigned();
            $table->integer('no_urut_surat');
            $table->foreign('jenis_surat_id')->references('id')->on('jenis_surats');
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
        Schema::dropIfExists('surat_internals');
    }
}
