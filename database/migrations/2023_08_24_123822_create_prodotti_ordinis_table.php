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
        Schema::create('prodotti_ordini', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_ordine')->index();
			$table->integer('id_magazzino')->index();
			$table->integer('codice_articolo')->index();
			$table->double('quantita',10,2);
			$table->double('prezzo_unitario',2);
			$table->integer('aliquota');
			$table->double('subtotale',2);
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
        Schema::dropIfExists('prodotti_ordini');
    }
};
