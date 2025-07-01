<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appaltibox', function (Blueprint $table) {
            $table->id();
            $table->integer('idapp')->index();
            $table->string('m_e',1);
            $table->tinyInteger('id_box');
            $table->tinyInteger('rowbox');
            $table->integer('id_lav');
            $table->tinyInteger('responsabile_mezzo');
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
        Schema::dropIfExists('appaltibox');
    }
};
