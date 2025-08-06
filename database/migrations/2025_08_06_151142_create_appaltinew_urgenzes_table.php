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
        Schema::create('appaltinew_urgenze', function (Blueprint $table) {
            $table->id();
            $table->integer('idapp')->index();
            $table->integer('id_ditta');
            $table->integer('id_servizio');
            $table->integer('id_lavoratore');
            $table->text('descrizione');
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
        Schema::dropIfExists('appaltinew_urgenzes');
    }
};
