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
        Schema::create('fornitori', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->string('ragione_sociale',100);
			$table->string('partita_iva',20);
			$table->string('codice_fiscale',20);
			$table->string('indirizzo',200)->nullable();
			$table->string('cap',40)->nullable();
			$table->string('comune',150)->nullable();
			$table->string('provincia',10)->nullable();
			$table->string('pec',100)->nullable();
			$table->string('telefono',30)->nullable();
			$table->string('sdi',30)->nullable();
			$table->string('iban',30)->nullable();
			$table->string('tipo_pagamento',50)->nullable();
			$table->string('cognome_referente',50)->nullable();
			$table->string('nome_referente',50)->nullable();
			$table->string('telefono_referente',30)->nullable();
        });	
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fornitori');
    }
};
