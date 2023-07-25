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
			$table->date('da_data_n')->after('proprieta')->nullable();
			$table->date('a_data_n')->after('da_data_n')->nullable();
			$table->integer('km_noleggio')->after('a_data_n')->nullable();
			$table->double('importo_noleggio',10,2)->after('km_noleggio')->nullable();
			$table->integer('km_alert_mail')->after('importo_noleggio')->nullable();
			$table->integer('gg_alert_mail')->after('km_alert_mail')->nullable();
			$table->string('servizi_noleggio',100)->after('gg_alert_mail')->nullable();			
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
