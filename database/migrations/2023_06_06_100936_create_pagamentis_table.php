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
        Schema::create('pagamenti', function (Blueprint $table) {
            $table->id();
			$table->integer('id_doc')->index()->nullable();
			$table->string('id_temp');
			$table->integer('tipo_pagamento');
			$table->date('data_scadenza')->nullable();
			$table->double('importo',10,2)->nullable();
			$table->string('persona',100)->nullable();
			$table->string('coordinate',191)->nullable();
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
        Schema::dropIfExists('pagamentis');
    }
};
