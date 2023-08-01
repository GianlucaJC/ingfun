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
			$table->string('tipo_durata_noleggio',1)->after('proprieta')->nullable();
			$table->integer('durata_noleggio')->after('tipo_durata_noleggio')->nullable();
			$table->integer('durata_noleggio_gg')->after('durata_noleggio')->nullable();			
			$table->string('tipo_alert_noleggio',1)->after('durata_noleggio_gg')->nullable();
			$table->integer('alert_mail')->after('tipo_alert_noleggio')->nullable();
			$table->integer('km_noleggio_remote')->after('alert_mail')->nullable();

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
