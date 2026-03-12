<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJamMasukPulangLupaabsen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lupa_absens', function (Blueprint $table) {
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->text('aktivitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lupa_absens', function (Blueprint $table) {
            //
        });
    }
}
