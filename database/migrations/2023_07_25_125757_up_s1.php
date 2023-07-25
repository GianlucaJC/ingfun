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
		Schema::table('parco_scheda_mezzo', function ($table) {
			$table->integer('mezzo_riparazione')->nullable();
			$table->string('officina_riferimento',100)->nullable();
			$table->date('data_consegna_riparazione')->nullable();
			$table->double('importo_preventivo',10,2)->nullable();
			$table->double('importo_fattura',10,2)->nullable();
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
