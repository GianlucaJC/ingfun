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
        Schema::create('servizi_ditte', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_ditta')->index();
			$table->integer('id_servizio')->index();
			$table->double('importo_ditta',10,3)->nullable();
			$table->double('aliquota',10,2)->nullable();
			$table->double('importo_lavoratore',10,3)->nullable();
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
        Schema::dropIfExists('servizi_dittes');
    }
};
