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

        Schema::create('articoli_preventivo', function (Blueprint $table) {
            $table->id();
			$table->integer('id_doc')->index()->nullable();
			$table->integer('ordine')->nullable();
			$table->string('id_temp');
			$table->string('codice',30)->index()->nullable();
			$table->string('descrizione');
			$table->double('quantita',10,2);
			$table->string('um',20)->nullable();
			$table->integer('id_um')->nullable();
			$table->double('prezzo_unitario',10,2);
			$table->double('sconto',10,2)->nullable();
			$table->double('subtotale',10,2);
			$table->integer('aliquota');
			$table->integer('id_aliquota')->nullable();
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
        Schema::dropIfExists('articoli_preventivos');
    }
};
