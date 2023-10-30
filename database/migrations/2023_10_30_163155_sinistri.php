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
        Schema::create('sinistri', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_mezzo')->index();
			$table->datetime('dataora')->nullable();
			$table->integer('mezzo_marciante')->nullable();
			$table->text('citta')->nullable();
			$table->text('provincia')->nullable();
			$table->text('indirizzo')->nullable();
			$table->longText("descrizione")->nullable();
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
        //
    }
};
