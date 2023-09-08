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
        Schema::create('movimenti_carico', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_ordine')->index();
			$table->integer('id_prodotto')->index();
			$table->integer('id_magazzino')->index();
			$table->integer('qta');
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
        Schema::dropIfExists('movimenti_carico');
    }
};
