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
        Schema::create('appaltinew_altro', function (Blueprint $table) {
            $table->id();
            $table->integer('idapp')->index();
            $table->string('m_e',1);
            $table->tinyInteger('box');
            $table->string('targa1',10)->nullable();
            $table->string('targa2',10)->nullable();
            $table->integer('ditta');
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
        Schema::dropIfExists('appaltinew_altro');
    }
};
