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
        Schema::create('parco_riparazioni', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_mezzo')->index();
			$table->string('officina_riferimento',100)->nullable();
			$table->date('data_consegna_prevista')->nullable();
			$table->date('data_consegna_riparazione')->nullable();
			$table->double('importo_preventivo',10,2)->nullable();
			$table->double('importo_fattura',10,2)->nullable();
			$table->integer("mezzo_marciante")->nullable();
			$table->integer("mezzo_manutenzione")->nullable();			
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
        Schema::dropIfExists('parco_riparazioni');
    }
};
