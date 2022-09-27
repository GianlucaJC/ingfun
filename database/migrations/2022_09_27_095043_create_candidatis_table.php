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
        Schema::create('candidatis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->string('cognome',40);
			$table->string('nome',60);
			$table->string('indirizzo',150);
			$table->string('cap',40);
			$table->string('comune',150);
			$table->string('provincia',10);
			$table->string('codfisc',16);
			$table->date('datanasc');
			$table->string('comunenasc',150);
			$table->string('pro_nasc',10);
			$table->string('email',150);
			$table->string('telefono',20);
			$table->string('pec',150);
			$table->string('iban',27)->nullable();
			$table->integer('stato_occ')->nullable();
			$table->integer('rdc')->nullable();
			$table->integer('cat_pro')->nullable();
			$table->integer('titolo_studio')->nullable();
			$table->string('istituto_conseguimento',150)->nullable();
			$table->string('anno_mese',7)->nullable();
			$table->string('patenti',70)->nullable();
			$table->integer('capacita')->nullable();
			$table->string('libero_p',1)->nullable();
			$table->integer('tipo_contratto')->nullable();
			$table->integer('ore_sett')->nullable();
			$table->integer('soc_ass')->nullable();
			$table->integer('divisione')->nullable();			
			$table->integer('area_impiego')->nullable();
			$table->integer('mansione')->nullable();
			$table->integer('centro_costo')->nullable();
			$table->double('netto_concordato',10,2)->nullable();
			$table->double('costo_azienda',10,2)->nullable();
			$table->string('zona_lavoro',100)->nullable();
			$table->integer('n_scarpe')->nullable();
			$table->string('taglia',20)->nullable();
			$table->string('status_candidatura',1)->nullable();
			$table->longText('note')->nullable();
			





        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidatis');
    }
};
