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
        Schema::create('ordini_fornitore', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->integer('id_fornitore')->index();
			$table->date('data_ordine');
			$table->date('data_presunta_arrivo_merce')->nullable();
			$table->integer('stato_ordine');
			$table->integer('id_sede_consegna');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordini_fornitore');
    }
};
